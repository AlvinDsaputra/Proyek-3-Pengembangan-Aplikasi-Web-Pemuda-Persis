<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvincialBranchSeeder::class,   // wajib sebelum UserSeeder (NPA referensi kode PC)
            RolePermissionSeeder::class,
            OrganizationalPeriodSeeder::class,
            UserSeeder::class,
            LetterTypeSeeder::class,
            LetterDestinationCodeSeeder::class,
        ]);
    }
}
