<?php

namespace App\Services\Concerns;

use App\Services\CloudinaryService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

/**
 * BrandService::logo, CategoryService::image, and UserService::avatar were
 * three copies of the same "if a new file was sent, upload it, swap the
 * url/public_id, delete the old asset" logic. This trait is that logic
 * written once — each service just tells it which fields to read/write.
 *
 * Requires the using class to have a `protected CloudinaryService $cloudinary`.
 */
trait ReplacesCloudinaryImage
{
    /**
     * @param  array  $data  the create/update payload (mutated copy returned)
     * @param  string  $fileField  e.g. 'logo', 'image', 'avatar' — the incoming file key
     * @param  string  $urlField  e.g. 'logo_url' — where the Cloudinary URL is stored
     * @param  string  $publicIdField  e.g. 'logo_public_id' — where the Cloudinary public_id is stored
     * @param  string  $folder  Cloudinary folder, e.g. 'brands'
     * @param  ?string  $oldPublicId  the record's current public_id, if updating
     */
    protected function replaceImage(
        array $data,
        string $fileField,
        string $urlField,
        string $publicIdField,
        string $folder,
        ?string $oldPublicId = null,
    ): array {

        if (! isset($data[$fileField]) || ! $data[$fileField] instanceof UploadedFile) {
            unset($data[$fileField]);
            return $data;
        }

        $uploaded = $this->cloudinary->upload($data[$fileField], $folder, ['resource_type' => 'image']);

        $data[$urlField] = $uploaded['url'];
        $data[$publicIdField] = $uploaded['public_id'];
        unset($data[$fileField]);

        if ($oldPublicId) {
            $this->cloudinary->destroy($oldPublicId);
        }

        return $data;
    }
}