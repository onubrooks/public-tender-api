<?php

namespace App\Services;

use App\Jobs\ProcessXslFile;
use App\Models\Contract;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Facades\Excel;

class UploadService
{
    public static function queueUpload($file, $title = null)
    {
        // $path = $file->store('uploads', 'local');
        $name = $file->getClientOriginalName();
        $status = Storage::disk('public')->put('uploads/' . $name, fopen($file, 'r+'));
        $items = Excel::toCollection(new Contract(), $file);
        $path = "public/uploads/$name";

        $upload = Upload::create([
            'title' => $title,
            'file_path' => $path,
            'number_of_rows' => $items[0]->count() - 1,
            'status' => 'queued'
        ]);
        ProcessXslFile::dispatch($upload)->delay(now()->addMinutes(2));

        return $upload;
    }

    public static function getUploads()
    {
        return Upload::select('title', 'status', 'file_path')->get();
    }

    public static function getUploadStatus($upload_id)
    {
        return Upload::select('status')->find($upload_id);
    }

    public static function getQueuedUploads()
    {
        return Upload::select('title', 'status', 'file_path')->where('status', 'queued')->get();
    }

    public static function getProcessingUploads()
    {
        return Upload::select('title', 'status', 'file_path')->where('status', 'processing')->get();
    }

    public static function getProcessedUploads()
    {
        return Upload::select('title', 'status', 'file_path')->where('status', 'processed')->get();
    }
}