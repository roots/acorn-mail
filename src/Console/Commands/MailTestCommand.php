<?php

namespace Roots\AcornMail\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Roots\AcornMail\AcornMail;

class MailTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test
                            {--to= : The email address to send the test email to.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the WordPress SMTP mailer';

    /**
     * The mail errors.
     */
    protected array $errors = [];

    /**
     * The mail options.
     */
    protected array $options = [
        'From' => 'From',
        'FromName' => 'From Name',
        'Host' => 'Host',
        'Password' => 'Password',
        'Port' => 'Port',
        'SMTPSecure' => 'Encryption',
        'Subject' => 'Subject',
        'Timeout' => 'Timeout',
        'Username' => 'Username',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $package = app()->make(AcornMail::class);

        if (! $package->configured()) {
            $this->components->error('The mail SMTP configuration is not set.');

            return;
        }

        $instance = $this;

        add_action('phpmailer_init', function ($phpmailer) use ($instance) {
            $phpmailer->SMTPDebug = 1;
            $phpmailer->Debugoutput = fn ($error) => $instance->errors[] = $error;

            $config = collect($phpmailer)
                ->filter(fn ($value, $key) => in_array($key, array_keys($this->options)))
                ->map(fn ($value, $key) => $key === 'Password' ? Str::mask($value, '*', 0) : $value)
                ->map(fn ($value, $key) => Str::finish($this->options[$key], ': ') . (is_null($value) || empty($value) ? 'Not set' : "<fg=blue>{$value}</>"));

            $this->components->bulletList($config);
        });

        $recipient = $this->option('to') ?: $this->askForRecipient();

        if (! $this->validEmail($recipient)) {
            $this->components->error('The specified email address is invalid.');

            return;
        }

        $this->components->info("Sending a test email to <fg=blue>{$recipient}</>...");

        $mail = wp_mail(
            $recipient,
            'Test Email',
            'This is a test email from WordPress.'
        );

        if ($mail) {
            $this->components->info('The test email was sent successfully.');

            return;
        }

        $errors = collect($this->errors)
            ->filter(fn ($error) => Str::startsWith($error, 'SMTP Error: '));

        if ($errors->isEmpty()) {
            $this->components->error('The test email failed to send.');

            return;
        }

        $errors = $errors
            ->map(fn ($error) => "  {$error}")
            ->map(fn ($error) => str_replace("\n", "\n  ", $error));

        $this->components->error('The test email failed to send. The following errors were encountered:');
        $this->line($errors->first());
    }

    /**
     * Ask the email address to send the test email to.
     */
    protected function askForRecipient(): string
    {
        $recipient = $this->components->ask('What email address should the test email be sent to?', get_bloginfo('admin_email'));

        if (! $this->validEmail($recipient)) {
            $this->components->error('The specified email address is invalid.');

            return $this->askForRecipient();
        }

        return $recipient;
    }

    /**
     * Determine if the email is valid.
     */
    protected function validEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
