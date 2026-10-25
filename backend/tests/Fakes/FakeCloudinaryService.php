<?php

namespace Tests\Fakes;

use App\Services\CloudinaryService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Drop-in replacement for CloudinaryService bound in tests (see
 * FakesExternalServices::fakeCloudinary()) — returns deterministic fake
 * URLs/public_ids instead of calling the real Cloudinary API, so the test
 * suite runs offline and fast. Records every call so tests can assert on
 * "was an upload/destroy actually attempted" without caring about the
 * specific fake values.
 */
class FakeCloudinaryService extends CloudinaryService
{
    public array $uploaded = [];

    public array $destroyed = [];

    public function __construct()
    {
        // Deliberately skip the parent constructor — it requires
        // CLOUDINARY_URL to be set, which tests shouldn't need.
    }

    public function upload(UploadedFile|string $file, string $folder = 'misc', array $options = []): array
    {
        $publicId = $folder.'/'.Str::random(12);

        $result = [
            'url' => "https://res.cloudinary.com/fake/image/upload/{$publicId}.jpg",
            'public_id' => $publicId,
            'width' => 800,
            'height' => 600,
            'format' => 'jpg',
            'bytes' => 12345,
        ];

        $this->uploaded[] = $result;

        return $result;
    }

    public function uploadMany(array $files, string $folder = 'misc', array $options = []): array
    {
        return array_map(fn ($file) => $this->upload($file, $folder, $options), $files);
    }

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
        $this->destroyed[] = $publicId;

        return true;
    }

    public function destroyMany(array $publicIds, string $resourceType = 'image'): bool
    {
        foreach ($publicIds as $id) {
            $this->destroy($id, $resourceType);
        }

        return true;
    }
}
