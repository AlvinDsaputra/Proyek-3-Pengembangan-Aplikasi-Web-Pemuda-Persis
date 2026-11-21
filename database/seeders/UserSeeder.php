<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\SharedCore\Models\OrganizationalPeriod;
use Modules\SharedCore\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->createUsers();
        $this->assignRoles($users);

        // Perbarui activated_by periode aktif ke akun admin
        OrganizationalPeriod::where('is_active', true)
            ->whereNull('activated_by')
            ->update(['activated_by' => $users['admin']->id]);
    }

    private function createUsers(): array
    {
        $definitions = [
            'admin' => [
                'name'      => 'Admin Test',
                'email'     => 'admin@test.com',
                'npa'       => 'ADM-001',
                'is_active' => true,
            ],
            'ketua' => [
                'name'      => 'Ketua Test',
                'email'     => 'ketua@test.com',
                'npa'       => 'KTU-001',
                'is_active' => true,
            ],
            'sekretaris' => [
                'name'      => 'Sekretaris Test',
                'email'     => 'sekretaris@test.com',
                'npa'       => 'SKR-001',
                'is_active' => true,
            ],
            'bidang_admin' => [
                'name'      => 'Bidang Administrasi Test',
                'email'     => 'bidang.admin@test.com',
                'npa'       => 'BDA-001',
                'is_active' => true,
            ],
            'anggota' => [
                'name'      => 'Anggota Test',
                'email'     => 'anggota@test.com',
                'npa'       => 'ANG-001',
                'is_active' => true,
            ],
        ];

        $map = [];
        foreach ($definitions as $key => $data) {
            $map[$key] = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['password' => Hash::make('password')])
            );
        }

        return $map;
    }

    private function assignRoles(array $users): void
    {
        $roles   = Role::pluck('id', 'slug');
        $period  = OrganizationalPeriod::where('is_active', true)->first();
        $admin   = $users['admin'];
        $now     = now();

        $assignments = [
            // Admin: role permanen, tidak terikat periode
            ['user' => $admin,                 'role' => 'superadmin',         'period_id' => null],
            // Pengurus aktif: terikat periode kepengurusan
            ['user' => $users['ketua'],         'role' => 'ketua',              'period_id' => $period?->id],
            ['user' => $users['sekretaris'],    'role' => 'sekretaris',         'period_id' => $period?->id],
            ['user' => $users['bidang_admin'],  'role' => 'bidang_administrasi','period_id' => $period?->id],
            ['user' => $users['anggota'],       'role' => 'anggota',            'period_id' => null],
        ];

        foreach ($assignments as $a) {
            $a['user']->roles()->syncWithoutDetaching([
                $roles[$a['role']] => [
                    'period_id'   => $a['period_id'],
                    'assigned_by' => $admin->id,
                    'assigned_at' => $now,
                ],
            ]);
        }
    }
}
