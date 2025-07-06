<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role === 'viewer') {
            $files = File::where('is_public', true)->latest()->get();
        } elseif ($user->role === 'admin') {
            $files = File::with('user')->latest()->get();
        } elseif ($user->role === 'user') {
            $files = File::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('is_public', true);
            })->latest()->get();
        } else {
            return response()->json(['message' => 'User role not recognized'], 403);
        }

        return response()->json(['files' => $files]);
    }




    public function upload(Request $request)
    {
        if (! $request->hasFile('file')) {
            return response()->json([
                'error' => 'No File'
            ], 400); // 400 Bad Request
        }

        $request->validate([
            'file' => 'required|file|max:10240',
            'is_public' => 'nullable|boolean',
        ]);

        $uploadedFile = $request->file('file');

        // التخزين داخل storage/app/public/files
        $path = $uploadedFile->store('files', 'public');

        $file = File::create([
            'user_id'       => $request->user()->id,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_path'   => $path,
            'file_type'     => $uploadedFile->getClientOriginalExtension(),
            'is_public' => $request->input('is_public', false)
        ]);

        return response()->json([
            'message' => 'Seccessfully sent file',
            'file'    => $file
        ]);
    }
    public function destroy(File $file)
    {
        $user = Auth::user();
        if (! $file) {
            return response()->json(['message' => 'file not found'], 404);
        }
        if (! $user->role == 'admin' && $file->user_id != $user->id) {
            return response()->json(['message' => 'unathorized user'], 403);
        }
        Storage::disk('public')->delete($file->stored_path);
        $file->delete();
        return response()->json(['message' => 'File Deleted Successfully']);
    }
    public function download(File $file)
    {

        $user = Auth::user();
        if (! $file) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $canDownload = false;

        if ($user->role === 'admin') {
            $canDownload = true;
        } elseif ($user->role === 'user') {
            $canDownload = $file->user_id === $user->id || $file->is_public;
        } elseif ($user->role === 'viewer') {
            $canDownload = $file->is_public;
        }

        if (! $canDownload) {
            return response()->json(['message' => 'You are not allowed to download this file'], 403);
        }

        $filePath = storage_path('app/public/' . $file->stored_path);
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found']);
        }
        return response()->download($filePath, $file->original_name);
    }
}
