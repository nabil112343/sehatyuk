<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
       User::updateOrCreate(['email' => 'admin@sehatyuk.com'], [
    'name' => 'Admin SehatYuk', 'phone' => '085212345678',
    'password' => Hash::make(env('ADMIN_PASSWORD')), 'role' => 'admin',
]);
       User::updateOrCreate(['email' => 'petugas@sehatyuk.com'], [
    'name' => 'Petugas SehatYuk', 'phone' => '081200000002',
    'password' => Hash::make(env('PETUGAS_PASSWORD')), 'role' => 'petugas',
]);

        $data = [
            ['UM', 'Poli Umum', 'Pemeriksaan kesehatan umum dan konsultasi awal.',
                ['dr. Anggun Rahmati', 'Dokter Umum', 'Senin,Selasa,Rabu,Kamis,Jumat,Sabtu', '08:00', '13:00', 25]],
            ['GG', 'Poli Gigi & Mulut', 'Perawatan gigi, mulut, dan scaling rutin.',
                ['drg. Shyntia Dewi Aryani, Sp.KG', 'Spesialis Konservasi Gigi', 'Senin,Rabu,Jumat', '09:00', '13:00', 12]],
            ['AN', 'Poli Anak (Sp.A)', 'Pemeriksaan dan tumbuh kembang anak.',
                ['Dr. dr. Dora Darussalam, Sp.A (K)', 'Spesialis Anak', 'Senin,Selasa,Kamis,Sabtu', '09:00', '13:00', 15]],
            ['OG', 'Poli Kebidanan & Kandungan (Sp.OG)', 'Pemeriksaan kehamilan dan kesehatan reproduksi.',
                ['dr. Dian Paramita, Sp.OG', 'Spesialis Kebidanan & Kandungan', 'Selasa,Rabu,Jumat', '09:00', '14:00', 15]],
            ['MT', 'Poli Mata (Sp.M)', 'Pemeriksaan dan perawatan kesehatan mata.',
                ['dr. Eva Mardalena, Sp.M', 'Spesialis Mata', 'Senin,Kamis,Sabtu', '08:30', '12:30', 15]],
            ['PD', 'Poli Penyakit Dalam (Sp.PD)', 'Pemeriksaan dan penanganan penyakit dalam.',
                ['dr. Andi Wijaya, Sp.PD', 'Spesialis Penyakit Dalam', 'Senin,Rabu,Jumat', '08:30', '12:30', 15]],
        ];

        foreach ($data as [$code, $name, $desc, $d]) {
            $poli = Poli::updateOrCreate(['code' => $code], ['name' => $name, 'description' => $desc]);
            Doctor::updateOrCreate(['poli_id' => $poli->id, 'name' => $d[0]], [
                'specialty' => $d[1], 'days' => $d[2],
                'start_time' => $d[3], 'end_time' => $d[4], 'quota' => $d[5],
            ]);
        }

        $this->call(ArticleSeeder::class);
    }
}
