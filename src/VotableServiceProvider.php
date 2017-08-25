<?php

namespace JrMiranda\Votable;

use Illuminate\Support\ServiceProvider;

class VotableServiceProvider extends ServiceProvider
{
  /**
  * Bootstrap the application services.
  *
  * @return void
  */
  public function boot()
  {
    $this->publishes([
      __DIR__.'/votable.php' => config_path('votable.php')
    ]);

    $this->loadMigrationsFrom(__DIR__.'/migrations');
  }

  /**
  * Register the application services.
  *
  * @return void
  */
  public function register()
  {
    //
  }
}
