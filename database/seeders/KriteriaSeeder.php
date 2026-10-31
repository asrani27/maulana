<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $kriteriaConfig = [
            [
                'kode' => 'C1',
                'nama' => 'Pekerjaan',
                'bobot' => 0.15,
                'atribut' => 'benefit',
                'subs' => [
                    ['nama' => 'Tidak Bekerja', 'nilai' => 1.00],
                    ['nama' => 'Petani Karet', 'nilai' => 0.75],
                    ['nama' => 'Pedagang', 'nilai' => 0.50],
                    ['nama' => 'PNS / Pegawai Swasta', 'nilai' => 0.25],
                ],
            ],
            [
                'kode' => 'C2',
                'nama' => 'Jumlah Anak / Tanggungan',
                'bobot' => 0.30,
                'atribut' => 'benefit',
                'subs' => [
                    ['nama' => '> 9 anak', 'nilai' => 1.00],
                    ['nama' => '6 - 9 anak', 'nilai' => 0.75],
                    ['nama' => '3 - 6 anak', 'nilai' => 0.50],
                    ['nama' => '0 - 2 anak', 'nilai' => 0.25],
                ],
            ],
            [
                'kode' => 'C3',
                'nama' => 'Kondisi Rumah',
                'bobot' => 0.30,
                'atribut' => 'benefit',
                'subs' => [
                    ['nama' => 'Sangat Darurat / Luas < 8m2', 'nilai' => 1.00],
                    ['nama' => 'Lantai & Dinding Non-Permanen', 'nilai' => 0.75],
                    ['nama' => 'Dinding Semi-Permanen', 'nilai' => 0.50],
                    ['nama' => 'Permanen / Listrik Memadai', 'nilai' => 0.25],
                ],
            ],
            [
                'kode' => 'C4',
                'nama' => 'Penghasilan Per Bulan',
                'bobot' => 0.25,
                'atribut' => 'cost',
                'subs' => [
                    ['nama' => 'Rp 0 - Rp 300.000', 'nilai' => 1.00],
                    ['nama' => 'Rp 300.001 - Rp 800.000', 'nilai' => 0.75],
                    ['nama' => 'Rp 800.001 - Rp 1.600.000', 'nilai' => 0.50],
                    ['nama' => '> Rp 1.600.000', 'nilai' => 0.25],
                ],
            ],
        ];

        foreach ($kriteriaConfig as $kc) {
            $kriteria = Kriteria::firstOrCreate(
                ['kode' => $kc['kode']],
                [
                    'nama_kriteria' => $kc['nama'],
                    'bobot' => $kc['bobot'],
                    'atribut' => $kc['atribut'],
                ]
            );

            foreach ($kc['subs'] as $sub) {
                SubKriteria::firstOrCreate(
                    [
                        'kriteria_id' => $kriteria->id,
                        'nama_subkriteria' => $sub['nama'],
                    ],
                    [
                        'nilai' => $sub['nilai'],
                    ]
                );
            }
        }
    }
}
