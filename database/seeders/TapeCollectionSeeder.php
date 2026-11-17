<?php

namespace Database\Seeders;

use App\Models\TapeCollection;
use Illuminate\Database\Seeder;

/**
 * 15 data dummy koleksi pita analog (PTA-006 s.d. PTA-020).
 * Melengkapi 5 data awal di TapeCollectionSeeder (PTA-001 s.d. PTA-005).
 *
 * Jalankan:  php artisan db:seed --class=TapeCollectionSeeder
 */
class TapeCollectionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'code' => 'PTA-006', 'name' => 'Pidato Kenegaraan 1971', 'media_format' => 'open_reel',
                'description' => 'Rekaman pidato kenegaraan di hadapan sidang parlemen.',
                'condition' => ['physical_condition' => 'rusak', 'damage_description' => 'Lapisan oksida mengelupas di bagian awal gulungan.', 'has_mold' => false, 'has_sticky_shed' => true, 'assessed_at' => '2026-10-14'],
                'actions' => [
                    ['action_name' => 'Baking pita 6 jam', 'priority' => 'tinggi', 'status' => 'proses', 'planned_date' => '2026-11-16', 'notes' => 'Suhu 50°C, pantau tiap 2 jam.'],
                    ['action_name' => 'Digitalisasi 96kHz/24bit', 'priority' => 'tinggi', 'status' => 'rencana', 'planned_date' => '2026-11-24', 'notes' => null],
                ],
            ],
            [
                'code' => 'PTA-007', 'name' => 'Dongeng Anak Pengantar Tidur', 'media_format' => 'compact_cassette',
                'description' => 'Kaset dongeng produksi rumahan tahun 1985.',
                'condition' => ['physical_condition' => 'baik', 'damage_description' => null, 'has_mold' => false, 'has_sticky_shed' => false, 'assessed_at' => '2026-10-15'],
                'actions' => [
                    ['action_name' => 'Digitalisasi', 'priority' => 'rendah', 'status' => 'selesai', 'planned_date' => '2026-11-05', 'notes' => 'Hasil dalam format WAV dan FLAC.'],
                ],
            ],
            [
                'code' => 'PTA-008', 'name' => 'Ceramah Pengajian Kampung 1979', 'media_format' => 'compact_cassette',
                'description' => 'Rekaman ceramah mingguan di masjid kampung.',
                'condition' => ['physical_condition' => 'kritis', 'damage_description' => 'Jamur tebal pada permukaan pita dan pita menempel antarlilitan.', 'has_mold' => true, 'has_sticky_shed' => true, 'assessed_at' => '2026-10-16'],
                'actions' => [
                    ['action_name' => 'Isolasi koleksi (karantina jamur)', 'priority' => 'tinggi', 'status' => 'selesai', 'planned_date' => '2026-10-20', 'notes' => 'Disimpan terpisah dalam kantong tertutup.'],
                    ['action_name' => 'Pembersihan jamur dengan alkohol isopropil', 'priority' => 'tinggi', 'status' => 'proses', 'planned_date' => '2026-11-17', 'notes' => 'Gunakan masker dan sarung tangan nitril.'],
                    ['action_name' => 'Baking pita', 'priority' => 'sedang', 'status' => 'rencana', 'planned_date' => '2026-11-27', 'notes' => null],
                ],
            ],
            [
                'code' => 'PTA-009', 'name' => 'Sandiwara Radio Saur Sepuh', 'media_format' => 'open_reel',
                'description' => 'Episode sandiwara radio populer era 1980-an.',
                'condition' => ['physical_condition' => 'cukup', 'damage_description' => 'Pita menggulung tidak rata (cinching).', 'has_mold' => false, 'has_sticky_shed' => false, 'assessed_at' => '2026-10-17'],
                'actions' => [
                    ['action_name' => 'Gulung ulang (rewinding) tegangan rendah', 'priority' => 'sedang', 'status' => 'selesai', 'planned_date' => '2026-11-02', 'notes' => null],
                    ['action_name' => 'Digitalisasi', 'priority' => 'sedang', 'status' => 'rencana', 'planned_date' => '2026-11-26', 'notes' => null],
                ],
            ],
            [
                'code' => 'PTA-010', 'name' => 'Catatan Lapangan Peneliti Etnomusikologi', 'media_format' => 'microcassette',
                'description' => 'Catatan suara penelitian lapangan di Nusa Tenggara.',
                'condition' => ['physical_condition' => 'cukup', 'damage_description' => 'Suara berfluktuasi akibat pita mengendur.', 'has_mold' => false, 'has_sticky_shed' => false, 'assessed_at' => '2026-10-19'],
                'actions' => [
                    ['action_name' => 'Pindah gulungan ke cangkang baru', 'priority' => 'sedang', 'status' => 'rencana', 'planned_date' => '2026-11-23', 'notes' => 'Cangkang pengganti sudah tersedia.'],
                ],
            ],
            [
                'code' => 'PTA-011', 'name' => 'Konser Jazz Jakarta 1993', 'media_format' => 'dat',
                'description' => 'Master konser jazz, rekaman dua kanal.',
                'condition' => ['physical_condition' => 'baik', 'damage_description' => null, 'has_mold' => false, 'has_sticky_shed' => false, 'assessed_at' => '2026-10-20'],
                'actions' => [
                    ['action_name' => 'Digitalisasi via pemutar DAT', 'priority' => 'sedang', 'status' => 'proses', 'planned_date' => '2026-11-14', 'notes' => 'Pemutar DAT dijadwalkan servis setelah proses ini.'],
                ],
            ],
            [
                'code' => 'PTA-012', 'name' => 'Lagu Dangdut Album Perdana', 'media_format' => 'cartridge_8track',
                'description' => 'Rilisan 8-track album perdana grup dangdut lokal.',
                'condition' => ['physical_condition' => 'rusak', 'damage_description' => 'Bantalan busa pita (pinch roller pad) hancur dan menempel.', 'has_mold' => false, 'has_sticky_shed' => true, 'assessed_at' => '2026-10-21'],
                'actions' => [
                    ['action_name' => 'Ganti bantalan busa kartrid', 'priority' => 'tinggi', 'status' => 'rencana', 'planned_date' => '2026-11-21', 'notes' => null],
                    ['action_name' => 'Digitalisasi', 'priority' => 'sedang', 'status' => 'rencana', 'planned_date' => '2026-11-30', 'notes' => null],
                ],
            ],
            [
                'code' => 'PTA-013', 'name' => 'Rekaman Sidang Adat 1968', 'media_format' => 'open_reel',
                'description' => 'Dokumentasi sidang adat; bernilai historis tinggi.',
                'condition' => ['physical_condition' => 'kritis', 'damage_description' => 'Pita rapuh, sobek di beberapa titik, dan berbau asam cuka (vinegar syndrome).', 'has_mold' => true, 'has_sticky_shed' => false, 'assessed_at' => '2026-10-22'],
                'actions' => [
                    ['action_name' => 'Stabilisasi dan penyambungan pita (splicing)', 'priority' => 'tinggi', 'status' => 'proses', 'planned_date' => '2026-11-12', 'notes' => 'Gunakan splicing tape khusus arsip.'],
                    ['action_name' => 'Digitalisasi prioritas', 'priority' => 'tinggi', 'status' => 'rencana', 'planned_date' => '2026-11-19', 'notes' => 'Lakukan dengan kecepatan putar rendah.'],
                ],
            ],
            [
                'code' => 'PTA-014', 'name' => 'Kuliah Umum Sejarah Nusantara', 'media_format' => 'compact_cassette',
                'description' => 'Rekaman kuliah umum, dua sisi kaset.',
                'condition' => ['physical_condition' => 'baik', 'damage_description' => null, 'has_mold' => false, 'has_sticky_shed' => false, 'assessed_at' => '2026-10-23'],
                'actions' => [],
            ],
            [
                'code' => 'PTA-015', 'name' => 'Musik Latar Film Dokumenter', 'media_format' => 'open_reel',
                'description' => 'Master musik latar film dokumenter tahun 1975.',
                'condition' => ['physical_condition' => 'cukup', 'damage_description' => 'Sedikit sticky shed, masih bisa diputar setelah perlakuan.', 'has_mold' => false, 'has_sticky_shed' => true, 'assessed_at' => '2026-10-26'],
                'actions' => [
                    ['action_name' => 'Baking pita 4 jam', 'priority' => 'sedang', 'status' => 'selesai', 'planned_date' => '2026-11-09', 'notes' => 'Pita stabil setelah baking.'],
                    ['action_name' => 'Digitalisasi 192kHz/24bit', 'priority' => 'sedang', 'status' => 'proses', 'planned_date' => '2026-11-18', 'notes' => null],
                ],
            ],
            [
                'code' => 'PTA-016', 'name' => 'Surat Suara Perantau', 'media_format' => 'microcassette',
                'description' => 'Surat berbentuk rekaman suara antarkeluarga, 1987.',
                'condition' => ['physical_condition' => 'rusak', 'damage_description' => 'Casing patah dan pita kusut di dalam cangkang.', 'has_mold' => false, 'has_sticky_shed' => false, 'assessed_at' => '2026-10-27'],
                'actions' => [
                    ['action_name' => 'Bongkar cangkang dan luruskan pita', 'priority' => 'tinggi', 'status' => 'rencana', 'planned_date' => '2026-11-20', 'notes' => null],
                ],
            ],
            [
                'code' => 'PTA-017', 'name' => 'Siaran Olahraga PON 1996', 'media_format' => 'dat',
                'description' => 'Komentar siaran radio langsung pertandingan.',
                'condition' => ['physical_condition' => 'cukup', 'damage_description' => 'Dropout sporadis pada menit ke-20 hingga 25.', 'has_mold' => false, 'has_sticky_shed' => false, 'assessed_at' => '2026-10-28'],
                'actions' => [
                    ['action_name' => 'Pembersihan kepala baca pemutar', 'priority' => 'rendah', 'status' => 'selesai', 'planned_date' => '2026-11-06', 'notes' => null],
                    ['action_name' => 'Digitalisasi dan restorasi dropout digital', 'priority' => 'sedang', 'status' => 'rencana', 'planned_date' => '2026-12-01', 'notes' => null],
                ],
            ],
            [
                'code' => 'PTA-018', 'name' => 'Kompilasi Lagu Keroncong', 'media_format' => 'cartridge_8track',
                'description' => 'Kompilasi lagu keroncong lawas.',
                'condition' => ['physical_condition' => 'cukup', 'damage_description' => 'Sambungan pita (splice) internal mulai lepas.', 'has_mold' => false, 'has_sticky_shed' => false, 'assessed_at' => '2026-11-02'],
                'actions' => [
                    ['action_name' => 'Perbaikan splice internal kartrid', 'priority' => 'sedang', 'status' => 'rencana', 'planned_date' => '2026-11-28', 'notes' => null],
                ],
            ],
            [
                'code' => 'PTA-019', 'name' => 'Rekaman Upacara Hari Kemerdekaan 1975', 'media_format' => 'open_reel',
                'description' => 'Siaran upacara bendera lengkap dengan sambutan.',
                'condition' => ['physical_condition' => 'baik', 'damage_description' => null, 'has_mold' => false, 'has_sticky_shed' => false, 'assessed_at' => '2026-11-04'],
                'actions' => [
                    ['action_name' => 'Digitalisasi 96kHz/24bit', 'priority' => 'rendah', 'status' => 'rencana', 'planned_date' => '2026-12-03', 'notes' => 'Menunggu slot mesin digitalisasi.'],
                ],
            ],
            [
                'code' => 'PTA-020', 'name' => 'Wawancara Tokoh Pers 1984', 'media_format' => 'compact_cassette',
                'description' => null,
                'condition' => ['physical_condition' => 'rusak', 'damage_description' => 'Pita melar sehingga kecepatan putar tidak stabil (wow and flutter).', 'has_mold' => false, 'has_sticky_shed' => false, 'assessed_at' => '2026-11-10'],
                'actions' => [
                    ['action_name' => 'Digitalisasi dengan koreksi kecepatan', 'priority' => 'tinggi', 'status' => 'rencana', 'planned_date' => '2026-11-25', 'notes' => 'Koreksi dilakukan pada tahap pasca-proses.'],
                ],
            ],
        ];

        foreach ($data as $row) {
            // firstOrCreate: aman dijalankan berulang tanpa menggandakan data
            $koleksi = TapeCollection::firstOrCreate(
                ['code' => $row['code']],
                collect($row)->only(['name', 'media_format', 'description'])->all()
            );

            if ($koleksi->wasRecentlyCreated) {
                $koleksi->condition()->create($row['condition']);
                $koleksi->actions()->createMany($row['actions']);
            }
        }
    }
}