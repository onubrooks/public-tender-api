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
        $path = Storage::putFile('public/uploads', $file);

        // $result = $file->storeOnCloudinaryAs('public_tender_uploads', time() . $file->getClientOriginalName());
        // $path = $result->getSecurePath();

        $file_meta = [
            'size' => $file->getSize(),
            'extension' => $file->extension(),
            'original_file_name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'time_uploaded' => now(),
        ];

        // if file size is less than 2mb, read and save it's row count
        if($file->getSize() < 2000000)
        {
            $readerType = $file->extension() === 'xls' ? \Maatwebsite\Excel\Excel::XLS : \Maatwebsite\Excel\Excel::XLSX;
            $items = Excel::toCollection(new Contract(), $file, 'local', $readerType);
            $row_count = $items[0]->count() - 1;
        } else {
            $row_count = 'unknown due to huge file size';
        }

        $upload = Upload::create([
            'title' => $title,
            'file_path' => $path,
            'number_of_rows' => $row_count,
            'status' => 'queued',
            'file_meta' => json_encode($file_meta),
        ]);
        ProcessXslFile::dispatch($upload)->delay(now()->addMinutes(1));

        return $upload;
    }

    public static function getUploads()
    {
        return Upload::select('title', 'status', 'file_path', 'file_meta')->get();
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