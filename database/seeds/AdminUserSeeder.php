<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $email = env('ADMIN_EMAIL');
        if ($email === null) {
            throw new Exception('ADMIN_EMAIL environment variable not set');
        }

        $password = env('ADMIN_PASSWORD');
        if ($password === null) {
            throw new Exception('ADMIN_PASSWORD environment variable not set');
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->password = app('hash')->make($password);
        $user->name = 'Admin User';

        $user->save();
        $this->command->info("Admin user $email created successfully.");
    }
}
