<?php

namespace Roots\AcornMail;

use Illuminate\Support\ServiceProvider;

class AcornMailServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Console\Commands\MailConfigCommand::class,
            Console\Commands\MailTestCommand::class,
        ]);

        $this->app->singleton('Roots\AcornMail', fn () => AcornMail::make($this->app));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Roots\AcornMail');
    }
}
