<?php

namespace App\Console\Commands;

use App\User;
use Hash;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'cocoa:admin';

    protected $description = 'Creates admin user from ADMIN_USER and ADMIN_PASSWORD env vars.';

    public function handle()
    {
        $adminUser = User::firstOrNew([
            'email' => env('ADMIN_EMAIL'),
            'name' => 'Cocoaheads Admin'
        ]);

        $adminUser->password = Hash::make(env('ADMIN_PASSWORD'));
        $adminUser->save();

        $this->info("Admin User id $adminUser->id");
    }
}
