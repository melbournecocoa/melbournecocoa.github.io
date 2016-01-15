<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(SponsorTableSeeder::class);
         $this->call(EventTableSeeder::class);
         $this->call(PostsTableSeeder::class);
    }
}
