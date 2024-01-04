<?php

namespace Roots\AcornMail\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
     * The error collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $errors = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $package = app('Roots\AcornMail');

        if (! $package->configured()) {
            $this->components->error('The mail SMTP configuration is not set.');

            return;
        }

        $instance = $this;

        add_action('phpmailer_init', function ($phpmailer) use ($instance) {
            $phpmailer->SMTPDebug = 1;
            $phpmailer->Debugoutput = fn ($error) => $instance->errors[] = $error;
        });

        $recipient = $this->option('to') ?: $this->askForRecipient();

        $this->components->info("Sending a test email to <fg=blue>{$recipient}</>...");

        $result = wp_mail(
            $recipient,
            'Acorn Mail Test',
            'This is a test email from Acorn Mail.'
        );

        if ($result) {
            $this->components->info('The test email was sent successfully.');

            return;
        }

        $this->errors = collect($this->errors)
            ->filter(fn ($error) => Str::startsWith($error, 'SMTP Error: '));

        if ($this->errors->isEmpty()) {
            $this->components->error('The test email failed to send.');
        }

        $this->errors = $this->errors
            ->map(fn ($error) => "  {$error}")
            ->map(fn ($error) => str_replace("\n", "\n  ", $error));

        $this->components->error('The test email failed to send. The following errors were encountered:');
        $this->line($this->errors->first());
    }

    /**
     * Ask the email address to send the test email to.
     */
    protected function askForRecipient(): string
    {
        $recipient = $this->components->ask('What email address should the test email be sent to?', get_bloginfo('admin_email'));

        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $this->components->error('The specified email address is invalid.');

            return $this->askForRecipient();
        }

        return $recipient;
    }
}
