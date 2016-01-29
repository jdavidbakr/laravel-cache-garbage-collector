<?php

namespace jdavidbakr\LaravelCacheGarbageCollector;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

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
        \Config::set('filesystems.disks.fcache',$cacheDisk);
        $expired_file_count = 0;
        $active_file_count = 0;

        // Grab the cache files
        $files = \Storage::disk('fcache')->allFiles();

        // Loop the files and get rid of any that have expired
        foreach($files as $key=>$cachefile) {
            // Ignore this file
            if($cachefile == '.gitignore') {
                continue;
            }

            // Grab the contents of the file
            $contents = \Storage::disk('fcache')->get($cachefile);

            // Get the expiration time
            $expire = substr($contents, 0, 10);

            // See if we have expired
            if(time() >= $expire) {
                // Delete the file
                \Storage::disk('fcache')->delete($cachefile);
                $expired_file_count++;
            } else {
                $active_file_count++;
            }
        }

        $this->line('Total expired cache files removed: '.$expired_file_count);
        $this->line('Total active cache files remaining: '.$active_file_count);
    }
}