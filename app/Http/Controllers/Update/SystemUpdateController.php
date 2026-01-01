<?php

namespace App\Http\Controllers\Update;

use App\Http\Controllers\Controller;
use App\Services\UpdaterService;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class SystemUpdateController extends Controller
{
    protected $updater;
    protected $updatesDir;

    public function __construct(UpdaterService $updater)
    {
        $this->updater = $updater;
        $this->updatesDir = storage_path('app/updates');
        if (!File::exists($this->updatesDir)) {
            File::makeDirectory($this->updatesDir, 0755, true);
        }
        if (!File::exists($this->updatesDir.'/backups')) {
            File::makeDirectory($this->updatesDir.'/backups', 0755, true);
        }
    }


    public function index()
    {
        $uploaded = null;

        // updates folder from first zip detect
        $files = glob($this->updatesDir.'/*.zip');

        if (count($files)) {
            $uploaded = basename($files[0]);
        }

        $backups = [];

        foreach (glob($this->updatesDir.'/backups/*.zip') as $f) {
            $backups[] = [
                'name' => basename($f),
                'time' => (new \DateTime())
                    ->setTimestamp(filemtime($f))
                    ->setTimezone(new \DateTimeZone('Asia/Dhaka'))
                    ->format('Y-m-d h:i:s A')
            ];
        }

        usort($backups, fn($a, $b) => strtotime($b['time']) - strtotime($a['time']));

        return view('system_update.index', compact('uploaded','backups'));
    }


    public function upload(Request $request)
    {
        $request->validate([
            'update_zip' => 'required|file|mimes:zip'
        ]);

        $file = $request->file('update_zip');

        // Original filename
        $originalName = $file->getClientOriginalName();

        // destination
        $dest = $this->updatesDir . '/' . $originalName;

        // if uploaded.zip than delete
        foreach (glob($this->updatesDir.'/*.zip') as $old) {
            File::delete($old);
        }

        // new name move
        $file->move($this->updatesDir, $originalName);

        // session  filename save
        session(['uploaded_zip_name' => $originalName]);

        return back()->with('success', 'Update ZIP uploaded successfully.');
    }


    // Create backup and return download response
    public function backup(Request $request)
    {
        // optional token check if provided
        if ($request->has('token') && $request->token !== env('UPDATE_API_TOKEN')) {
            return back()->with('error', 'Invalid token for backup.');
        }

        $backupPath = $this->updater->createBackup(); // returns absolute path or false
        if (!$backupPath) {
            return back()->with('error', 'Failed to create backup.');
        }

        // return download response
        return response()->download($backupPath);
    }

    // Download existing backup file by name
    public function downloadBackup($file)
    {
        $path = $this->updatesDir . '/backups/' . basename($file);
        if (!File::exists($path)) abort(404);
        return response()->download($path);
    }




    public function run(Request $request)
    {
        // require token
        if ($request->token !== env('UPDATE_API_TOKEN')) {
            return back()->with('error', 'Invalid update token');
        }

        // detect uploaded zip (any zip file)
        $files = glob($this->updatesDir . '/*.zip');

        if (!$files || !count($files)) {
            return back()->with('error', 'No uploaded ZIP found. Upload first.');
        }

        // take the first uploaded zip
        $zipPath = $files[0];

        // perform update using service
        $result = $this->updater->performUpdateFromUploadedZip($zipPath);

        if ($result['ok']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }


    ////delete old backup
    public function deleteBackup($file)
    {
        $path = $this->updatesDir . '/backups/' . basename($file);
        if (!File::exists($path)) {
            return back()->with('error', 'Backup file not found.');
        }

        try {
            File::delete($path);
            return back()->with('success', 'Backup deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete backup: ' . $e->getMessage());
        }
    }
}
