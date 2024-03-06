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
            ->withNoArgs()
            ->once()
            ->andReturn([
                'directory/filename'
            ]);
        $mock->shouldReceive('get')
            ->once()
            ->with('directory/filename')
            ->andReturn(Carbon::now()->subMinute()->timestamp.':the cache data');
        $mock->shouldReceive('delete')
            ->once()
            ->with('directory/filename');

        $mock->shouldReceive('allDirectories')
            ->withNoArgs()
            ->once()
            ->andReturn([
                'directory'
            ]);
        $mock->shouldReceive('allFiles')
            ->with('directory')
            ->once()
            ->andReturn([]);
        $mock->shouldReceive('deleteDirectory')
            ->with('directory')
            ->once();

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
                'directory/filename'
            ]);
        $mock->shouldReceive('get')
            ->once()
            ->with('directory/filename')
            ->andReturn(Carbon::now()->addMinute()->timestamp.':the cache data');
        $mock->shouldReceive('delete')
            ->times(0)
            ->with('directory/filename');

        $mock->shouldReceive('allDirectories')
            ->withNoArgs()
            ->once()
            ->andReturn([
                'directory'
            ]);
        $mock->shouldReceive('allFiles')
            ->with('directory')
            ->once()
            ->andReturn([
                'filename',
            ]);
        $mock->shouldReceive('deleteDirectory')
            ->times(0)
            ->with('directory');

        Storage::shouldReceive('disk')
            ->with('fcache')
            ->andReturn($mock);
        $command = new LaravelCacheGarbageCollector;

        $command->handle();

        $this->assertTrue(true);
    }
}
