<?php

namespace Roots\AcornMail\Console\Commands;

use Composer\InstalledVersions;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MailConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the Acorn mail config';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $config = Str::finish(
            InstalledVersions::getInstallPath('roots/acorn'),
            '/config/mail.php'
        );

        $destination = $this->laravel->configPath('mail.php');

        if (file_exists($destination)) {
            $this->components->error('The mail config file already exists.');

            return;
        }

        file_put_contents(
            $this->laravel->configPath('mail.php'),
            file_get_contents($config)
        );

        $this->components->info('The mail config file has been published.');
    }
}
