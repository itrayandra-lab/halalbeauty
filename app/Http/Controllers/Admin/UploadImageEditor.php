<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploadImageEditor extends Controller
{
    # image handleler
    public function uploadImage(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
        
        $file = $request->file('file');
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        
        if (!in_array($extension, $allowedExtensions)) {
            Log::channel('security')->warning('Invalid file extension upload attempt', [
                'extension' => $extension,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
            return response()->json(['error' => 'Invalid file extension'], 400);
        }
        
        if (!in_array($mimeType, $allowedMimes)) {
            Log::channel('security')->warning('Invalid MIME type upload attempt', [
                'mime_type' => $mimeType,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
            return response()->json(['error' => 'Invalid file type'], 400);
        }
        
        if (!@getimagesize($file->getPathname())) {
            Log::channel('security')->warning('Invalid image content upload attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
            return response()->json(['error' => 'Invalid image content'], 400);
        }
        
        $randomName = 'image_' . Str::random(10) . '.' . $extension;
        $path = public_path('assets/app/image-editor');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $file->move($path, $randomName);
        $url = asset('assets/app/image-editor/' . $randomName);
        
        Log::channel('security')->info('Image uploaded successfully', [
            'filename' => $randomName,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        return response()->json(['url' => $url], 200);
    }

    public function deleteImage(Request $request)
    {
        $imagePath = $request->input('image_path');
        
        if (!$imagePath) {
            return response()->json(['error' => 'Image path required'], 400);
        }
        
        $imagePath = str_replace(['../', '..\\', '../', '..\\'], '', $imagePath);
        
        $allowedPaths = [
            'assets/app/image-editor/',
            'assets/app/posts/',
            'assets/app/user-images/',
            'assets/app/videos/',
            'assets/app/banners/',
            'assets/app/albums/',
            'assets/app/ads/'
        ];
        
        $isAllowed = false;
        foreach ($allowedPaths as $allowedPath) {
            if (strpos($imagePath, $allowedPath) === 0) {
                $isAllowed = true;
                break;
            }
        }
        
        if (!$isAllowed) {
            Log::channel('security')->critical('Unauthorized file deletion attempt', [
                'path' => $imagePath,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
            return response()->json(['error' => 'Unauthorized path'], 403);
        }
        
        $fullPath = public_path($imagePath);
        
        if (File::exists($fullPath)) {
            File::delete($fullPath);
            
            Log::channel('security')->info('File deleted', [
                'path' => $imagePath,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
            
            return response()->json(['success' => 'Image deleted successfully'], 200);
        }

        return response()->json(['error' => 'Image not found'], 404);
    }
}
