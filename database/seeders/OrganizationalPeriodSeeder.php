<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SharedCore\Models\OrganizationalPeriod;

class OrganizationalPeriodSeeder extends Seeder
{
    public function run(): void
    {
        OrganizationalPeriod::firstOrCreate(
            ['name' => 'Periode Kepengurusan 2025-2027'],
            [
                'start_date'   => '2025-01-01',
                'end_date'     => '2027-12-31',
                'is_active'    => true,
                'activated_by' => null, // diperbarui oleh UserSeeder setelah admin dibuat
                'activated_at' => now(),
            ]
        );
    }
}
