<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LaravelCacheGarbageCollectorTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $cacheDisk = [
            'driver'=>'local',
            'root'=>config('cache.stores.file.path')
        ];
        \Config::set('filesystems.disks.fcache',$cacheDisk);

        // Grab the cache files and count them
        $files = \Storage::disk('fcache')->allFiles();

    	$test_key = 'test';
    	$test_value = 'test-value';
        \Cache::remember($test_key,\Carbon\Carbon::now()->addSecond(1),function() use($test_value) {
            return $test_value;
        });
        $this->assertEquals($test_value, \Cache::get($test_key));

        // Sleep to let the cache expire
        sleep(5);

        $files = \Storage::disk('fcache')->allFiles();

        dd(count($files));

        $cacheDisk = [
            'driver'=>'local',
            'root'=>config('cache.stores.file.path')
        ];
        \Config::set('filesystems.disks.fcache',$cacheDisk);

        // Grab the cache files
        $files = \Storage::disk('fcache')->allFiles();


        $this->assertNull(\Cache::get($test_key));
    }
}
