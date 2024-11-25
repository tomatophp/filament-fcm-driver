<?php

namespace TomatoPHP\FilamentFcmDriver\Console;

use Illuminate\Console\Command;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;

class FilamentFcmDriverInstall extends Command
{
    use HandleStub;
    use RunCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'filament-fcm-driver:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install package and publish assets';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Install FCM Worker');
        $this->generateStubs(
            __DIR__ . '/../../stubs/firebase.stub',
            public_path('firebase-messaging-sw.js'),
            [
                'apiKey' => config('filament-fcm-driver.project.apiKey'),
                'authDomain' => config('filament-fcm-driver.project.authDomain'),
                'databaseURL' => config('filament-fcm-driver.project.databaseURL'),
                'projectId' => config('filament-fcm-driver.project.projectId'),
                'storageBucket' => config('filament-fcm-driver.project.storageBucket'),
                'messagingSenderId' => config('filament-fcm-driver.project.messagingSenderId'),
                'appId' => config('filament-fcm-driver.project.appId'),
                'measurementId' => config('filament-fcm-driver.project.measurementId'),
                'sound' => config('filament-fcm-driver.alert.sound') ? "var audio = new Audio('" . config('filament-fcm-driver.alert.sound') . "');\n audio.play();" : null,
            ]
        );
        $this->info('Filament FCM Driver installed successfully.');
    }
}
