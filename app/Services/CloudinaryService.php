<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('filesystems.disks.cloudinary.cloud'),
                'api_key' => config('filesystems.disks.cloudinary.key'),
                'api_secret' => config('filesystems.disks.cloudinary.secret'),
            ]
        ]);
    }

    public function uploadFile(
        UploadedFile $file,
        string $folder = 'uploads',
        array $options = []
    ): array {
        $upload = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            array_merge([
                'folder' => $folder,
                'resource_type' => 'auto',
                'use_filename' => true,
                'unique_filename' => true,
            ], $options)
        );

        return [
            'url' => $upload['secure_url'],
            'public_id' => $upload['public_id'],
        ];
    }

    public function deleteFile(string $publicId): bool
    {
        $result = $this->cloudinary->uploadApi()->destroy($publicId);

        return in_array(
            $result['result'] ?? null,
            ['ok', 'not found'],
            true
        );
    }

    public function getFileUrl(string $publicId, array $transformations = []): string
    {
        return $this->cloudinary->image($publicId)->toUrl();
    }
}
