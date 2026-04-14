<?php

namespace Roots\AcornMail;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Roots\Acorn\Application;

class SiteHealth
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
     * Instantiate the Acorn Mail Site Health.
     */
    public function __construct(private Application $app)
    {
        $this->acornMail = $this->app->make(AcornMail::class);
        $this->config = Collection::make($this->app->config->get('mail.mailers.smtp'))
            ->merge($this->app->config->get('mail.from'));

        add_filter('debug_information', [$this, 'addDebugInfo']);
    }

    /**
     * Make a new instance of Acorn Mail Site Health.
     */
    public static function make(Application $app): self
    {
        return new static($app);
    }

    /**
     * Add Acorn Mail info to Site Health debug info.
     */
    public function addDebugInfo($debug_info)
    {
        $configured = $this->acornMail->configured();

        $fields = [
            'status' => [
                'label' => __('Configuration Status'),
                'value' => $configured ? __('Configured') : __('Not configured'),
                'private' => false,
            ],
            'delivery_method' => [
                'label' => __('Delivery Method'),
                'value' => $configured ? __('SMTP') : __('WordPress Default'),
                'private' => false,
            ],
        ];

        if ($configured) {
            $config = collect($this->config)
                ->map(fn ($value, $key) => $key === 'password' ? Str::mask($value, '*', 0) : $value)
                ->filter();

            foreach ($config as $key => $value) {
                $fields[strtolower($key)] = [
                    'label' => __(Str::title($key)),
                    'value' => $value,
                    'private' => $key === 'password',
                ];
            }
        }

        $debug_info['acorn-mail'] = [
            'label' => __('Acorn Mail'),
            'fields' => $fields,
        ];

        return $debug_info;
    }
}
