<?php

namespace App\Services;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Thin wrapper around Cloudinary's Upload API.
 *
 * Requires: composer require cloudinary/cloudinary_php
 * Config:   set CLOUDINARY_URL in .env, e.g.
 *           CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
 *           The SDK reads this env var automatically — no extra config file needed.
 */
class CloudinaryService
{
    protected UploadApi $uploadApi;

    public function __construct()
    {
        if (!env('CLOUDINARY_URL')) {
            throw new RuntimeException('CLOUDINARY_URL is not set in .env');
        }

        $this->uploadApi = new UploadApi();
    }

    /**
     * Upload a single file (UploadedFile, local path, remote URL, or
     * base64 data URI — Cloudinary's upload() accepts all of these).
     *
     * @return array{url: string, public_id: string, width: ?int, height: ?int, format: ?string, bytes: ?int}
     */
    public function upload(UploadedFile|string $file, string $folder = 'misc', array $options = []): array
    {
        try {
            $result = $this->uploadApi->upload(
                $file instanceof UploadedFile ? $file->getRealPath() : $file,
                array_merge([
                    'folder' => $folder,
                    'resource_type' => 'image',
                    'overwrite' => true,
                    'unique_filename' => true,
                ], $options)
            );

            return [
                'url' => $result['secure_url'],
                'public_id' => $result['public_id'],
                'width' => $result['width'] ?? null,
                'height' => $result['height'] ?? null,
                'format' => $result['format'] ?? null,
                'bytes' => $result['bytes'] ?? null,
            ];
        } catch (ApiError $e) {
            Log::error('Cloudinary upload failed', ['folder' => $folder, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    /**
     * @param  UploadedFile[]|string[]  $files
     * @return array<int, array{url: string, public_id: string}>
     */
    public function uploadMany(array $files, string $folder = 'misc', array $options = []): array
    {
        return array_map(fn ($file) => $this->upload($file, $folder, $options), $files);
    }

    /**
     * Upload a new file and, if it succeeds, delete the old asset by its
     * public_id. Use this for avatar/logo "replace" flows so a failed
     * upload never orphans the record without an old-vs-new image and a
     * successful upload never leaves the old file dangling in storage.
     */
    public function replace(?string $oldPublicId, UploadedFile|string $newFile, string $folder = 'misc'): array
    {
        $result = $this->upload($newFile, $folder);

        if ($oldPublicId) {
            $this->destroy($oldPublicId);
        }

        return $result;
    }

    public function destroy(string $publicId, string $resourceType = 'image'): bool
    {
        try {
            $result = $this->uploadApi->destroy($publicId, ['resource_type' => $resourceType]);

            return ($result['result'] ?? null) === 'ok';
        } catch (ApiError $e) {
            Log::error('Cloudinary destroy failed', ['public_id' => $publicId, 'error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * @param  string[]  $publicIds
     */
    public function destroyMany(array $publicIds, string $resourceType = 'image'): bool
    {
        $success = true;
        try {
            foreach ($publicIds as $publicId) {
                if (!$this->destroy($publicId, $resourceType)) {
                    $success = false;
                }
            }

        } catch (ApiError $e) {
            Log::error('Cloudinary bulk destroy failed', ['public_ids' => $publicIds, 'error' => $e->getMessage()]);
            $success = false;
        } finally {
            return $success;
        }
    }
}