<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessJsonFile;

class UploadController extends Controller
{
    public function showForm()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json',
        ]);

        $path = $request->file('json_file')->store('uploads');

        // Отправка задания в очередь для обработки файла
        ProcessJsonFile::dispatch($path);

        return back()->with('success', 'File uploaded and processing started.');
    }
}
