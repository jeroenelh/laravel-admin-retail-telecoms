<?php

namespace Microit\LaravelAdminRetailTelecoms\Commands;

use DCN\RBAC\Models\Permission;
use DCN\RBAC\Models\Role;
use Illuminate\Console\Command;
use Microit\LaravelAdminRetailTelecoms\ResizeImage;

class ResizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin-retail-telecoms:resize-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resize images';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $public_path = public_path('images/uploads/');
        $storage_path = storage_path('images/');

        $public_images = $this->getDirContents($public_path);
        $storage_images = $this->getDirContents($storage_path);

        $public_filesize = 0;
        $storage_filesize = 0;

        // Filesizes
        foreach ($public_images as $index => $image) {
            $size = filesize($image);
            if ($size == 0) {
                $this->error("Deleted file 0kb: ".$image);
                unlink($image);
                unset($public_images[$index]);
            }
            $public_filesize += $size;
        }
        foreach ($storage_images as $index => $image) {
            $size = filesize($image);
            if ($size == 0) {
                $this->error("Deleted file 0kb: ".$image);
                unlink($image);
                unset($storage_filesize[$index]);
            }
            $storage_filesize += $size;
        }

        $this->info("Public fulesize:  ".$this->human_filesize($public_filesize));
        $this->info("Storage fulesize: ".$this->human_filesize($storage_filesize));

        // Delete old files
        foreach ($storage_images as $image) {
            if (!file_exists(str_replace($storage_path, $public_path, $image))) {
                $this->info("Delete storage file: ".$image);
                unlink($image);
            }
        }

        // Copy files to storage (and resize)
        foreach ($public_images as $image) {
            $storage_name = str_replace($public_path, $storage_path, $image);
            if (!file_exists($storage_name)) {
                // Generate dir if needed
                if (!is_dir(dirname($storage_name))) {
                    mkdir(dirname($storage_name), 0777, true);
                }

                // Copy
                if (!copy($image, $storage_name)) {
                    $this->error("Error while copying file: ".$image);
                    return;
                }

                if (filesize($storage_name) == 0) {
                    $this->error("No filesize: ".$image);
                    return;
                }

                // Resize
                $resizeObj = new ResizeImage($storage_name);
                $resizeObj->resizeImage(400, 400);
                $resizeObj->saveImage($image, 60);

                // Output
                $old_filesize = filesize($storage_name);
                $new_filesize = filesize($image);
                $procent = round(($new_filesize/$old_filesize)*100);
                $this->info("Copy & resize: ".$procent."% ".$image);
            }
        }

    }

    private function getDirContents($dir, &$results = array()){
        $files = scandir($dir);

        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path) && substr($value,0,1) != ".") {
                $results[] = $path;
            } else if(is_dir($path) && $value != "." && $value != "..") {
                $this->getDirContents($path, $results);
                //$results[] = $path;
            }
        }

        return $results;
    }

    private function human_filesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
}
