<?php

namespace Test;

use Mockery;
use Illuminate\Support\Carbon;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Storage;
use jdavidbakr\LaravelCacheGarbageCollector\LaravelCacheGarbageCollector;

class LaravelCacheGarbageCollectorTest extends TestCase
{
    /**
     * @test
     */
    public function it_removes_expired_cache_files()
    {
        $mock = Mockery::mock('disk');
        $mock->shouldReceive('allFiles')
            ->once()
            ->andReturn([
                'filename'
            ]);
        $mock->shouldReceive('get')
            ->once()
            ->with('filename')
            ->andReturn(Carbon::now()->subMinute()->timestamp.':the cache data');
        $mock->shouldReceive('delete')
            ->once()
            ->with('filename');
        Storage::shouldReceive('disk')
            ->with('fcache')
            ->andReturn($mock);
        $command = new LaravelCacheGarbageCollector;

        $command->handle();

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_doesnt_remove_non_expired_cache_entries()
    {
        $mock = Mockery::mock('disk');
        $mock->shouldReceive('allFiles')
            ->once()
            ->andReturn([
                'filename'
            ]);
        $mock->shouldReceive('get')
            ->once()
            ->with('filename')
            ->andReturn(Carbon::now()->addMinute()->timestamp.':the cache data');
        $mock->shouldReceive('delete')
            ->times(0)
            ->with('filename');
        Storage::shouldReceive('disk')
            ->with('fcache')
            ->andReturn($mock);
        $command = new LaravelCacheGarbageCollector;

        $command->handle();

        $this->assertTrue(true);
    }
}
