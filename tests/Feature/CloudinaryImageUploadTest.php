<?php

namespace Tests\Feature;

use App\Services\CloudinaryService;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CloudinaryImageUploadTest extends TestCase
{
    private CloudinaryService $cloudinaryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cloudinaryService = app(CloudinaryService::class);
    }

    /**
     * Test uploading an image to Cloudinary and retrieving public_id
     */
    public function test_upload_image_to_cloudinary()
    {
        // Skip if Cloudinary not configured
        if (!env('CLOUDINARY_CLOUD_NAME') || !env('CLOUDINARY_API_KEY')) {
            $this->markTestSkipped('Cloudinary credentials not configured');
        }

        // Create a temporary test image
        $testImagePath = $this->createTestImage();

        try {
            // Upload the image
            $result = $this->cloudinaryService->uploadFile(
                new UploadedFile(
                    $testImagePath,
                    'test-image.jpg',
                    'image/jpeg',
                    null,
                    true
                ),
                'test-uploads'
            );

            // Assert response structure
            $this->assertArrayHasKey('url', $result);
            $this->assertArrayHasKey('public_id', $result);
            $this->assertNotEmpty($result['url']);
            $this->assertNotEmpty($result['public_id']);

            // Verify URL is HTTPS
            $this->assertStringStartsWith('https://', $result['url']);

            // Verify public_id contains folder
            $this->assertStringContainsString('test-uploads', $result['public_id']);

            echo "\n✅ Cloudinary Upload Success!";
            echo "\n   Public ID: " . $result['public_id'];
            echo "\n   URL: " . $result['url'];

            // Cleanup: delete from Cloudinary
            $this->cloudinaryService->deleteFile($result['public_id']);
            echo "\n   Cleanup: Image deleted from Cloudinary";

            $this->assertTrue(true);

        } finally {
            // Clean up local test file
            if (file_exists($testImagePath)) {
                unlink($testImagePath);
            }
        }
    }

    /**
     * Create a simple test image
     */
    private function createTestImage(): string
    {
        $path = storage_path('test_image.jpg');

        // Create a 100x100 image
        $image = imagecreatetruecolor(100, 100);
        $blue = imagecolorallocate($image, 0, 102, 204);
        imagefill($image, 0, 0, $blue);

        $white = imagecolorallocate($image, 255, 255, 255);
        imagestring($image, 5, 25, 45, 'Test Image', $white);

        imagejpeg($image, $path, 90);
        imagedestroy($image);

        return $path;
    }
}