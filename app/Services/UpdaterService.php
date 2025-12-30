<?php
namespace App\Services;

use ZipArchive;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class UpdaterService
{
    protected $updatesDir;
    protected $backupDir;
    protected $tmpDir;

    public function __construct()
    {
        $this->updatesDir = storage_path('app/updates');
        $this->backupDir = $this->updatesDir . '/backups';
        $this->tmpDir = $this->updatesDir . '/tmp';
        if (!file_exists($this->backupDir)) mkdir($this->backupDir, 0755, true);
        if (!file_exists($this->tmpDir)) mkdir($this->tmpDir, 0755, true);
    }

    /**
     * Create backup ZIP of app (returns absolute path or false)
     */
    public function createBackup()
    {
        $name = 'backup-'.date('Ymd-His').'.zip';
        $zipPath = $this->backupDir . DIRECTORY_SEPARATOR . $name;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== true) {
            return false;
        }

        // Paths to include (adjust if needed)
        $paths = [
            base_path('app'),
            base_path('bootstrap'),
            base_path('config'),
            base_path('database'),
            base_path('public'),
            base_path('resources'),
            base_path('routes'),
            base_path('composer.json'),
            base_path('composer.lock'),
            base_path('.env'),
            base_path('package.json'),
        ];

        foreach ($paths as $p) {
            if (is_dir($p)) {
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($p, \RecursiveDirectoryIterator::SKIP_DOTS));
                foreach ($files as $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relative = substr($filePath, strlen(base_path()) + 1);
                        $zip->addFile($filePath, $relative);
                    }
                }
            } elseif (file_exists($p)) {
                $zip->addFile($p, basename($p));
            }
        }

        $zip->close();
        return $zipPath;
    }

    /**
     * Perform update using uploaded zip path
     * Returns ['ok' => bool, 'message' => string]
     */
    public function performUpdateFromUploadedZip($zipFile)
    {
        if (!file_exists($zipFile)) {
            return ['ok' => false, 'message' => 'Uploaded zip not found.'];
        }

        // create safety backup first
        $backup = $this->createBackup();
        if (!$backup) {
            return ['ok' => false, 'message' => 'Failed to create backup before update.'];
        }

        // extract to a unique tmp dir
        // $extractPath = $this->tmpDir . '/' . Str::random(8);
        // if (!mkdir($extractPath, 0755, true)) {
        //     return ['ok' => false, 'message' => 'Failed to create temp directory.'];
        // }

        $extractPath = $this->tmpDir;
        if (!file_exists($extractPath)) mkdir($extractPath, 0755, true);

        // clean tmp contents safely
        foreach (glob($extractPath.'/*') as $fileOrDir) {
            $this->rrmdir($fileOrDir);
        }





        $zip = new ZipArchive();
        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return ['ok' => false, 'message' => 'Cannot open uploaded zip.'];
        }

        // determine root inside extracted zip
        $zipRoot = trim(env('UPDATE_ZIP_ROOT', ''), "/\\");
        $source = $zipRoot ? $extractPath . DIRECTORY_SEPARATOR . $zipRoot : $extractPath;
        if (!is_dir($source)) {
            // fallback to extractPath
            $source = $extractPath;
        }

        // copy files from source -> base_path()
        $copied = $this->recursiveCopy($source, base_path());
        if (!$copied) {
            $this->rrmdir($extractPath);
            return ['ok' => false, 'message' => 'Failed copying files to app root.'];
        }

        // run composer if allowed
        if (filter_var(env('UPDATE_RUN_COMPOSER', 'false'), FILTER_VALIDATE_BOOLEAN)) {
            exec('composer install --no-dev --optimize-autoloader --no-interaction', $out, $code);
            if ($code !== 0) {
                $this->rrmdir($extractPath);
                return ['ok' => false, 'message' => 'Composer failed (exit '.$code.'). Output: '.implode("\n", $out)];
            }
        }

        // run migrations if allowed
        if (filter_var(env('UPDATE_RUN_MIGRATIONS', 'true'), FILTER_VALIDATE_BOOLEAN)) {
            Artisan::call('migrate', ['--force' => true]);

            // Run seeders after migration
            Artisan::call('db:seed', ['--force' => true]);
        }

        // cleanup tmp
        $this->rrmdir($extractPath);

        return ['ok' => true, 'message' => 'Update applied successfully. Backup: '.basename($backup)];
    }

    protected function recursiveCopy($src, $dst)
    {
        $dir = opendir($src);
        if (!$dir) return false;
        if (!file_exists($dst)) mkdir($dst, 0755, true);

        while (false !== ($file = readdir($dir))) {
            if ($file === '.' || $file === '..') continue;
            $srcPath = $src . DIRECTORY_SEPARATOR . $file;
            $dstPath = $dst . DIRECTORY_SEPARATOR . $file;

            if (is_dir($srcPath)) {
                if (!file_exists($dstPath)) mkdir($dstPath, 0755, true);
                $this->recursiveCopy($srcPath, $dstPath);
            } else {
                // try to make destination writable
                if (file_exists($dstPath)) @chmod($dstPath, 0666);
                if (!copy($srcPath, $dstPath)) {
                    closedir($dir);
                    return false;
                }
            }
        }
        closedir($dir);
        return true;
    }

    // protected function rrmdir($dir)
    // {
    //     if (!file_exists($dir)) return;
    //     $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
    //     $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
    //     foreach ($files as $file) {
    //         if ($file->isDir()) rmdir($file->getRealPath()); else unlink($file->getRealPath());
    //     }
    //     rmdir($dir);
    // }


    protected function rrmdir($dir)
    {
        if (!file_exists($dir)) return;

        $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            try {
                if ($file->isDir()) rmdir($file->getRealPath());
                else unlink($file->getRealPath());
            } catch (\Exception $e) {
                // ignore error, continue
            }
        }

        // Only remove the folder itself if it's not tmp root
        if (basename($dir) !== 'tmp') {
            @rmdir($dir);
        }
    }

}
