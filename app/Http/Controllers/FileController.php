<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index() {
        $files = File::all();
        foreach ($files as $file) {
            $file->full_url = Storage::disk('s3')->url($file->url);
        }
        return view('files.list', ['files' => $files]);
    }

    public function create() {
        return view('files.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);
    
        if ($request->hasFile('file')) {
            $extension  = request()->file('file')->getClientOriginalExtension();
            $fileName = time() .'_' . $request->name . '.' . $extension;
            
            $path = $request->file('file')->storeAs(
                'files',
                $fileName,
                's3'
            );
    
            File::create([
                'name' => $request->name,
                'url' => $path,
            ]);
    
            return redirect()->back()->with([
                'message' => "Image uploaded successfully",
            ]);
        }
    }
    
    public function destroy($id) {
        $file = File::findOrFail($id);

        // Xóa file khỏi S3
        if (Storage::disk('s3')->exists($file->url)) {
            Storage::disk('s3')->delete($file->url);
            $file->delete();

            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        }
    }
}
