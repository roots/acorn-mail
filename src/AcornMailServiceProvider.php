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
        $this->app->singleton(AcornMail::class, fn () => AcornMail::make($this->app));
        $this->app->singleton(Widget::class, fn () => Widget::make($this->app));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(
            __DIR__.'/../resources/views',
            'AcornMail',
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\MailConfigCommand::class,
                Console\Commands\MailTestCommand::class,
            ]);
        }

        $this->app->make(AcornMail::class);
        $this->app->make(Widget::class);
    }
}
