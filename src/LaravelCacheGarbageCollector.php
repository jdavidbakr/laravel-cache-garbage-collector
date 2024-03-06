<?php

namespace jdavidbakr\LaravelCacheGarbageCollector;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class LaravelCacheGarbageCollector extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:gc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Garbage-collect the cache files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // Make a storage disk for the cache location
        $cacheDisk = [
            'driver' => 'local',
            'root' => config('cache.stores.file.path')
        ];

        Config::set('filesystems.disks.fcache', $cacheDisk);

        // Grab the cache files
        $files = Storage::disk('fcache')->allFiles();

        foreach ($files as $cachefile) {
            if ($cachefile == '.gitignore') {
                continue;
            }

            try {
                $contents = Storage::disk('fcache')->get($cachefile);

                // Get the expiration time
                $expire = substr($contents, 0, 10);

                if (Carbon::now()->timestamp < $expire) {
                    continue;
                }

                Storage::disk('fcache')->delete($cachefile);
            } catch (FileNotFoundException $e) {
                continue;
            }
        }

        // Now that we've removed expired files, grab all directories
        $directories = Storage::disk('fcache')->allDirectories();

        foreach ($directories as $directory) {
            try {
                $directoryFiles = Storage::disk('fcache')->allFiles($directory);

                if (count($directoryFiles)) {
                    // We still have cache files in this directory, we don't want to remove it
                    continue;
                }

                Storage::disk('fcache')->deleteDirectory($directory);
            } catch (FileNotFoundException $e) {
                continue;
            }
        }

        return Command::SUCCESS;
    }
}
