# Laravel 5 Cached like/dislike System

This is a simple Laravel package for help the implementation of a simple vote system with caching.

## Installing

Install via composer:

```
composer require jrmiranda/votable:dev-master
```

Add the following provider in your `config/app.php`:

```
JrMiranda\Votable\VotableServiceProvider::class,
```

Publish the config file `config/votable.php`:

```
php artisan vendor:publish --provider="JrMiranda\Votable\VotableServiceProvider"
```

## Usage


## References


## License

MIT
