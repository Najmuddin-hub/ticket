<?php

namespace Database\Seeders;

use App\Models\ReportType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $items = [
            ['name' => 'Bekalan Air', 'category_id' => 1],
            ['name' => 'Lif', 'category_id' => 1],
            ['name' => 'Pencegah Kebakaran', 'category_id' => 1],
            ['name' => 'Kerosakan Pintu', 'category_id' => 1],
            ['name' => 'Pemadam Api', 'category_id' => 1],
            ['name' => 'Penyaman Udara', 'category_id' => 1],
            ['name' => 'Mesin Perakam Waktu', 'category_id' => 1],
            ['name' => 'Kebersihan Dalam Bangunan', 'category_id' => 1],
            ['name' => 'Mesin Air Sejuk/Panas', 'category_id' => 1],
            ['name' => 'Kerosakan Bangunan', 'category_id' => 1],
            ['name' => 'Peralatan Tandas Dan Plumbing', 'category_id' => 1],
            ['name' => 'Sanitac Bin', 'category_id' => 1],
            ['name' => 'Lampu Dalam Bangunan', 'category_id' => 2],
            ['name' => 'Lampu Luar Bangunan', 'category_id' => 2],
            ['name' => 'Bekalan Elektrik', 'category_id' => 2],
            ['name' => 'Pepasangan Elektrik', 'category_id' => 2],
            ['name' => 'Sistem Siaraya (PA System)', 'category_id' => 2],
            ['name' => 'Sistem Audio Visual', 'category_id' => 2],
            ['name' => 'Infrastruktur', 'category_id' => 3],
            ['name' => 'Perkakasan/Perisian', 'category_id' => 4],
            ['name' => 'Rangkaian & Capaian Internet', 'category_id' => 4],
            ['name' => 'Sistem Servis', 'category_id' => 4],
            ['name' => 'Sistem Operasi (OS)', 'category_id' => 4],
            ['name' => 'Aplikasi Sistem', 'category_id' => 4],
            ['name' => 'Perkhidmatan Lanskap', 'category_id' => 5],
            ['name' => 'Peralatan Pejabat', 'category_id' => 6],
            ['name' => 'Telefon', 'category_id' => 7],
            ['name' => 'Door Access', 'category_id' => 7],
            ['name' => 'CCTV', 'category_id' => 7],
            ['name' => 'Walkie Talkie', 'category_id' => 7],
        ];
    
        foreach ($items as $item){
            \App\Models\ReportType::updateOrInsert(
                ['name' => $item['name']], //check by name
                ['category_id' => $item['category_id']]
            );
        }
    }
}
