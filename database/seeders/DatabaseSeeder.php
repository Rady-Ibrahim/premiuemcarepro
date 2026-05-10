<?php

namespace Database\Seeders;

use App\Models\TicketDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ProviderSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TicketSeeder::class);
        // $this->call(TicketDetail::class);
        $this->call(CategorySeeder::class);
    }
}
