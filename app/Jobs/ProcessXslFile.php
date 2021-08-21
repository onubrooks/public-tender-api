<?php

namespace App\Jobs;

use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use App\Imports\ContractsImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProcessXslFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The podcast instance.
     *
     * @var \App\Models\Upload
     */
    protected $upload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Upload $upload)
    {
        $this->upload = $upload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $readerType =
        $readerType = json_decode($this->upload->file_meta)->extension === 'xls' ? \Maatwebsite\Excel\Excel::XLS : \Maatwebsite\Excel\Excel::XLSX;
        $this->upload->update(['status' => 'processing']);
        Excel::import(
            new ContractsImport($this->upload),
            $this->upload->file_path, //for cloudinary, use this: $this->getFile($this->upload->file_path), 
            null, 
            $readerType
        );
        $this->upload->update(['status' => 'processed']);
    }

    public function getFile($url)
    {
        //get name file by url and save in object-file
        $path_parts = pathinfo($url);
        $newPath = sys_get_temp_dir() . '\tmp\\';
        if (!is_dir($newPath)) {
            mkdir($newPath, 0777);
        }
        $newUrl = $newPath . $path_parts['basename'];
        copy($url, $newUrl);
        $filesystem = new Filesystem;

        $name = $filesystem->name($newUrl);
        $extension = $filesystem->extension($newUrl);
        $originalName = $name . '.' . $extension;
        $mimeType = 'application/octet-stream';
        $filesystem->mimeType($newUrl);
        $error = null;

        $file = new UploadedFile($newUrl, $originalName, $mimeType, $error, false);

        return $file;
    }
}
