<?php

declare(strict_types=1);

namespace Eightfold\Amos\Laravel;

use Illuminate\Support\ServiceProvider;

use Eightfold\Amos\Store;

use Eightfold\Markdown\Markdown;

class Provider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__."/Routes.php");

        $this->app->singleton(Store::class, function($app) {
            return $this->store();
        });

        $this->app->singleton(Markdown::class, function($app) {
            return $this->markdown();
        });
    }

    public function boot()
    {
    }

    abstract public function store(): Store;

    abstract public function markdown(): Markdown;
}
