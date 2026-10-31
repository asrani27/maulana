<?php

namespace Database\Seeders;

use App\Models\Keluarga;
use App\Models\Penilaian;
use App\Models\Periode;
use App\Models\SubKriteria;
use App\Services\SawCalculationService;
use Illuminate\Database\Seeder;

class KeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $periode = Periode::firstOrCreate(
            ['nama_periode' => 'Penyaluran PKH Tahap 1 2026'],
            [
                'tahun' => 2026,
                'status' => 'aktif',
            ]
        );

        $keluargaData = [
            [
                'no_kk' => '6303051201010001',
                'nik_kepala' => '6303051201850001',
                'nama_kepala_keluarga' => 'Faisal Rahman',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Handil Bakti RT. 01 RW. 01',
                'rt' => '001',
                'rw' => '001',
                'penilaian' => [
                    'C1' => 'Petani Karet',
                    'C2' => '3 - 6 anak',
                    'C3' => 'Sangat Darurat / Luas < 8m2',
                    'C4' => 'Rp 300.001 - Rp 800.000',
                ],
            ],
            [
                'no_kk' => '6303051201010002',
                'nik_kepala' => '6303051402820002',
                'nama_kepala_keluarga' => 'Sukmadiansyah',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Simpang Tiga RT. 02 RW. 01',
                'rt' => '002',
                'rw' => '001',
                'penilaian' => [
                    'C1' => 'Tidak Bekerja',
                    'C2' => '6 - 9 anak',
                    'C3' => 'Lantai & Dinding Non-Permanen',
                    'C4' => 'Rp 0 - Rp 300.000',
                ],
            ],
            [
                'no_kk' => '6303051201010003',
                'nik_kepala' => '6303051803880003',
                'nama_kepala_keluarga' => 'Rahdianoor',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Qubah Sari RT. 03 RW. 01',
                'rt' => '003',
                'rw' => '001',
                'penilaian' => [
                    'C1' => 'Pedagang',
                    'C2' => '0 - 2 anak',
                    'C3' => 'Dinding Semi-Permanen',
                    'C4' => 'Rp 800.001 - Rp 1.600.000',
                ],
            ],
            [
                'no_kk' => '6303051201010004',
                'nik_kepala' => '6303052204790004',
                'nama_kepala_keluarga' => 'Syaifullah',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Handil Balau RT. 04 RW. 02',
                'rt' => '004',
                'rw' => '002',
                'penilaian' => [
                    'C1' => 'Tidak Bekerja',
                    'C2' => '> 9 anak',
                    'C3' => 'Sangat Darurat / Luas < 8m2',
                    'C4' => 'Rp 0 - Rp 300.000',
                ],
            ],
            [
                'no_kk' => '6303051201010005',
                'nik_kepala' => '6303052805810005',
                'nama_kepala_keluarga' => 'Rustam Hadi',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Handil Anyar RT. 05 RW. 02',
                'rt' => '005',
                'rw' => '002',
                'penilaian' => [
                    'C1' => 'PNS / Pegawai Swasta',
                    'C2' => '0 - 2 anak',
                    'C3' => 'Permanen / Listrik Memadai',
                    'C4' => '> Rp 1.600.000',
                ],
            ],
        ];

        foreach ($keluargaData as $kd) {
            $keluarga = Keluarga::firstOrCreate(
                ['no_kk' => $kd['no_kk']],
                [
                    'nik_kepala' => $kd['nik_kepala'],
                    'nama_kepala_keluarga' => $kd['nama_kepala_keluarga'],
                    'jenis_kelamin' => $kd['jenis_kelamin'],
                    'alamat' => $kd['alamat'],
                    'rt' => $kd['rt'],
                    'rw' => $kd['rw'],
                    'desa' => 'Thaibah Raya',
                    'kecamatan' => 'Tatah Makmur',
                    'kabupaten' => 'Banjar',
                    'provinsi' => 'Kalimantan Selatan',
                ]
            );

            foreach ($kd['penilaian'] as $kode => $subName) {
                $subModel = SubKriteria::whereHas('kriteria', fn ($q) => $q->where('kode', $kode))
                    ->where('nama_subkriteria', $subName)
                    ->first();

                if ($subModel) {
                    Penilaian::updateOrCreate(
                        [
                            'periode_id' => $periode->id,
                            'keluarga_id' => $keluarga->id,
                            'kriteria_id' => $subModel->kriteria_id,
                        ],
                        [
                            'sub_kriteria_id' => $subModel->id,
                            'nilai' => $subModel->nilai,
                        ]
                    );
                }
            }
        }

        $sawService = new SawCalculationService;
        $sawService->saveCalculations($periode->id);
    }
}
