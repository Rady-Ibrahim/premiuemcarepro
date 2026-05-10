<?php

namespace Database\Seeders;

use App\Models\TicketDetail;
use Illuminate\Database\Seeder;

class TicketDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketDetail::factory()->count(5)->create();
    }
}
