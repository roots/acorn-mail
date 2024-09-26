<?php

namespace Roots\AcornMail;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Roots\Acorn\Application;

class Widget
{
    /**
     * The mail configuration.
     */
    protected Collection $config;

    /**
     * The AcornMail instance.
     */
    private AcornMail $acornMail;

    /**
     * Instantiate the Acorn Mail Widget.
     */
    public function __construct(private Application $app)
    {
        $this->acornMail = $this->app->make(AcornMail::class);
        $this->config = Collection::make($this->app->config->get('mail.mailers.smtp'))
            ->merge($this->app->config->get('mail.from'));

        add_action('wp_dashboard_setup', [$this, 'add']);
    }

    /**
     * Make a new instance of Acorn Mail Widget.
     */
    public static function make(Application $app): self
    {
        return new static($app);
    }

    /**
     * Add the Acorn Mail widget to the WordPress dashboard.
     */
    public function add()
    {
        wp_add_dashboard_widget('acorn_mail_widget', 'Acorn Mail', [$this, 'content']);
    }

    /**
     * Render the Acorn Mail widget content.
     */
    public function content()
    {
        if (! $this->acornMail->configured()) {

            echo view('AcornMail::widget', [
                'message' => wpautop('Acorn mail is <strong>not</strong> configured and is mimicking out-of-the-box WordPress email delivery.'),
                'config' => null,
            ]);

            return;
        }

        $config = collect($this->config)
            ->map(fn ($value, $key) => $key === 'password' ? Str::mask($value, '*', 0) : $value)
            ->mapWithKeys(fn ($value, $key) => [Str::title($key) => $value])
            ->filter();

        echo view('AcornMail::widget', [
            'message' => wpautop('Acorn mail is configured and will use SMTP for email delivery.'),
            'config' => $config,
        ]);
    }
}
