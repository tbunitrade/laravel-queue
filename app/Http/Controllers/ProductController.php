<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessJsonFile;
use Illuminate\Support\Facades\Storage;

    //
public function upload(Request $request)
{
    $request->validate([
        'json_file' => 'required|file|mimes:json',
    ]);

    $path = $request->file('json_file')->store('uploads');

    ProcessJsonFile::dispatch($path);

    return back()->with('status', 'File uploaded successfully.');
}

