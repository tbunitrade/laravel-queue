<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessJsonFile;

class FileUploadController extends Controller
{
    public function showForm()
    {
        return view('upload');
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json',
        ]);

        $path = $request->file('json_file')->store('uploads');

        ProcessJsonFile::dispatch($path);

        return back()->with('success', 'File uploaded and processing started.');
    }
}
