<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Package;
use Database\Seeders\PackageSeeder;
use Database\Seeders\TerminalSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Elmer R Huitz',
            'email' => 'admin@admin.com',
        ]);

        $this->call([
            TerminalSeeder::class,
            PackageSeeder::class,
        ]);

        Package::makeAllSearchable();
    }
}
