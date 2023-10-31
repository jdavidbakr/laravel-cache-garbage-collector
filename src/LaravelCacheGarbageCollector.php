<?php

namespace jdavidbakr\LaravelCacheGarbageCollector;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
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
     * @return mixed
     */
    public function handle()
    {
        // Make a storage disk for the cache location
        $cacheDisk = [
            'driver'=>'local',
            'root'=>config('cache.stores.file.path')
        ];

        Config::set('filesystems.disks.fcache', $cacheDisk);

        $expired_file_count = 0;
        $active_file_count = 0;

        // Grab the cache files
        $files = Storage::disk('fcache')->allFiles();

        foreach ($files as $key=>$cachefile) {
            if ($cachefile == '.gitignore') {
                continue;
            }

            try {
                $contents = Storage::disk('fcache')->get($cachefile);

                // Get the expiration time
                $expire = substr($contents, 0, 10);

                if (Carbon::now()->timestamp >= $expire) {
                    Storage::disk('fcache')->delete($cachefile);

                    $expired_file_count++;

                    continue;
                }

                $active_file_count++;
            } catch (FileNotFoundException $e) {
                continue;
            }
        }
    }
}
