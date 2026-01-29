<?php

namespace Roots\AcornMail;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Roots\Acorn\Application;

class AcornMail
{
    /**
     * The Application instance.
     */
    protected Application $app;

    /**
     * The mail configuration.
     */
    protected Collection $config;

    /**
     * Create a new Acorn Mail instance.
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = Collection::make($this->app->config->get('mail.mailers.smtp'))
            ->merge($this->app->config->get('mail.from'));

        if (! $this->configured()) {
            return;
        }

        $this->configureMail();
    }

    /**
     * Make a new instance of Acorn Mail.
     */
    public static function make(Application $app): self
    {
        return new static($app);
    }

    /**
     * Configure the WordPress PHPMailer instance.
     */
    protected function configureMail(): void
    {
        add_filter('phpmailer_init', function ($mail) {
            $mail->isSMTP();

            $mail->SMTPAuth = ($this->config->get('username') && $this->config->get('password'));
            $mail->Host = $this->config->get('host');
            $mail->Port = $this->config->get('port');
            $mail->Username = $this->config->get('username');
            $mail->Password = $this->config->get('password');
            $mail->Timeout = $this->config->get('timeout', $mail->Timeout);
            $mail->SMTPSecure = $this->config->get('encryption', $mail->SMTPSecure);

            $mail->setFrom(
                $this->fromAddress(),
                $this->fromName()
            );

            $mail->Sender = $mail->From;
        });
    }

    /**
     * Retrieve the mail from name.
     */
    protected function fromName(): string
    {
        $name = $this->config->get('name');

        return $name && ! Str::is($name, 'Example')
            ? $name
            : get_bloginfo('name', 'display');
    }

    /**
     * Retrieve the mail from address.
     */
    protected function fromAddress(): string
    {
        $address = $this->config->get('address');

        if ($address && ! Str::is($address, 'hello@example.com')) {
            return $address;
        }

        $domain = parse_url(home_url(), PHP_URL_HOST);

        return "noreply@{$domain}";
    }

    /**
     * Determine if the mailer is configured.
     */
    public function configured(): bool
    {
        if (! $this->app->isProduction()) {
            return $this->config->get('host') && $this->config->get('port');
        }

        return $this->config->get('host')
            && $this->config->get('port')
            && $this->config->get('username')
            && $this->config->get('password');
    }
}
