# LaravelCacheGarbageCollector

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]
[![Travis CI][ico-travis]][link-travis]

When using the file cache driver, Laravel creates the cache files but never purges expired ones. This can lead to
a situation where you have a large number of unused and irrelevant cache files, especially if you do a lot of short-term
caching in your system.

This package creates an artisan command cache:gc that will garbage-collect your cache files, removing any that have expired.
You may run this manually or include it in a schedule.

Thanks to [TerrePorter](http://laravel.io/user/TerrePorter) for his suggestion on [laravel.io](http://laravel.io/forum/01-28-2016-cache-file-garbage-collection)!

## Install

Via Composer

```bash
$ composer require jdavidbakr/laravel-cache-garbage-collector
```

Then add the service provider to `app/Console/Kernel.php` in the \$commands array:

```php
\jdavidbakr\LaravelCacheGarbageCollector\LaravelCacheGarbageCollector::class
```

## Usage

```bash
$ php artisan cache:gc
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email me@jdavidbaker.com instead of using the issue tracker.

## Credits

-   [Jon Baker][link-author]
-   [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/jdavidbakr/laravel-cache-garbage-collector.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/jdavidbakr/laravel-cache-garbage-collector/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/jdavidbakr/laravel-cache-garbage-collector.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/jdavidbakr/laravel-cache-garbage-collector.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jdavidbakr/laravel-cache-garbage-collector.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/jdavidbakr/laravel-cache-garbage-collector
[link-travis]: https://travis-ci.org/jdavidbakr/laravel-cache-garbage-collector
[link-scrutinizer]: https://scrutinizer-ci.com/g/jdavidbakr/laravel-cache-garbage-collector/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/jdavidbakr/laravel-cache-garbage-collector
[link-downloads]: https://packagist.org/packages/jdavidbakr/laravel-cache-garbage-collector
[link-author]: https://github.com/jdavidbakr
[link-contributors]: ../../contributors
