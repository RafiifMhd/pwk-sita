<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Period;

class CreatePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Period::create([
            'name'       => 'TC_Proposal 1',
            'type'       => 'Pengajuan Proposal',
            'start_date' => '2025-05-01',
            'end_date'   => '2025-06-30',
            'is_open'    => true,
        ]);
    }
}
