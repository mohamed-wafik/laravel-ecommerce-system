<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

/**
 * Generic file upload/delete for the admin panel, backed by Cloudinary.
 * Frontend uploads a file here first, gets back {url, public_id}, then
 * sends that pair along with the rest of the form (product images, brand
 * logo, category image, user avatar) to the relevant resource endpoint.
 *
 * Accepts standard image formats (png, jpg/jpeg, gif, webp, bmp, svg) plus
 * heic/avif, and — via `type=raw` — non-image files (pdf, csv, docx, etc.)
 * that Cloudinary can also host.
 */
class MediaController extends Controller
{
    public function __construct(protected CloudinaryService $cloudinary) {}

    /**
     * Extensions accepted for image uploads. Laravel's built-in `image`
     * rule only covers jpg/jpeg/png/bmp/gif/svg/webp, so this is spelled
     * out explicitly to also allow heic/avif/tiff/ico coming from mobile
     * cameras and design tools.
     */
    protected const IMAGE_MIMES = 'jpg,jpeg,png,gif,webp,bmp,svg,heic,heif,avif,tiff,ico';

    public function store(Request $request)
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:'.self::IMAGE_MIMES],
            'folder' => ['nullable', 'string', 'in:products,brands,categories,avatars'],
        ]);

        $result = $this->cloudinary->upload(
            $request->file('file'),
            $data['folder'] ?? 'misc',
            ['resource_type' => 'image']
        );

        return $this->created($result, 'Image uploaded');
    }

    public function storeMany(Request $request)
    {
        $data = $request->validate([
            'files' => ['required', 'array', 'max:10'],
            'files.*' => ['file', 'max:10240', 'mimes:'.self::IMAGE_MIMES],
            'folder' => ['nullable', 'string', 'in:products,brands,categories,avatars'],
        ]);

        $results = $this->cloudinary->uploadMany(
            $request->file('files'),
            $data['folder'] ?? 'misc',
            ['resource_type' => 'image']
        );

        return $this->created($results, 'Images uploaded');
    }

    /**
     * Non-image uploads (invoices, return-request attachments, etc.).
     * Cloudinary stores these under resource_type "raw".
     */
    public function storeDocument(Request $request)
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'max:20480', 'mimes:pdf,doc,docx,xls,xlsx,csv,txt'],
            'folder' => ['nullable', 'string'],
        ]);

        $result = $this->cloudinary->upload(
            $request->file('file'),
            $data['folder'] ?? 'documents',
            ['resource_type' => 'raw']
        );

        return $this->created($result, 'File uploaded');
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'public_id' => ['required', 'string'],
            'resource_type' => ['nullable', 'string', 'in:image,raw,video'],
        ]);

        $deleted = $this->cloudinary->destroy($data['public_id'], $data['resource_type'] ?? 'image');

        return $deleted
            ? $this->noContentSuccess('File deleted')
            : $this->error('Could not delete file', 502);
    }
}
