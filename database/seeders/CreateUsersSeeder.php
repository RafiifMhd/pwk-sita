<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
  
class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = [
            [
               'name'=>'Admin A',
               'email'=>'admin@sita',
               'type'=>1,
               'password'=> bcrypt('000'),
            ],
            [
               'name'=>'Admin B',
               'email'=>'admin2@sita',
               'type'=>1,
               'password'=> bcrypt('000'),
            ],
            [
               'name'=>'Dosen A',
               'nip'=>'000000000000 001',
               'wa_dos'=>'082188888888',
               'email'=>'dosen@sita',
               'tipe_dos'=>'Utama',
               'type'=> 2,
               'password'=> bcrypt('000'),
            ],
            [
               'name'=>'Dosen B',
               'nip'=>'000000000000 002',
               'wa_dos'=>'082288888888',
               'email'=>'dosen2@sita',
               'tipe_dos'=>'Utama',
               'type'=> 2,
               'password'=> bcrypt('000'),
            ],
            [
               'name'=>'Dosen Pendamping',
               'nip'=>'000000000000 003',
               'wa_dos'=>'082388888888',
               'email'=>'dosen3@sita',
               'tipe_dos'=>'Pendamping',
               'type'=> 2,
               'password'=> bcrypt('000'),
            ],
            [
               'name'=>'Mahasiswa A',
               'nim'=>'120140148',
               'email'=>'user@sita',
               'type'=>0,
               'password'=> bcrypt('000'),
            ],
            [
               'name'=>'Mahasiswa B',
               'nim'=>'120140143',
               'email'=>'user2@sita',
               'type'=>0,
               'password'=> bcrypt('000'),
            ],
        ];
    
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}