<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Report;
use App\Models\Comment;
use App\Models\Response;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pastikan folder images ada
        if (!Storage::disk('public')->exists('images')) {
            Storage::disk('public')->makeDirectory('images');
        }

        $this->command->info('==========================================');
        $this->command->info('Starting Database Seeding...');
        $this->command->info('==========================================');

        // Seed Users
        $this->command->info("\n[1/4] Seeding Users...");

        $guest = User::create([
            'name' => 'Ahmad Rizki',
            'email' => 'guest@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'GUEST',
        ]);

        $guest2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'GUEST',
        ]);

        $guest3 = User::create([
            'name' => 'Budi Prasetyo',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'GUEST',
        ]);

        $staff = User::create([
            'name' => 'Budi Santoso',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'STAFF',
        ]);

        $staff2 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'STAFF',
        ]);

        $headStaff = User::create([
            'name' => 'Dr. Andi Wijaya',
            'email' => 'headstaff@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'HEAD_STAFF',
        ]);

        $this->command->info("✓ Created 6 users (3 GUEST, 2 STAFF, 1 HEAD_STAFF)");

        // Seed Reports dengan gambar
        $this->command->info("\n[2/4] Seeding Reports with Images...");
        $reports = $this->seedReports($guest, $guest2, $guest3);

        // Seed Comments
        $this->command->info("\n[3/4] Seeding Comments...");
        $this->seedComments($reports, [$guest, $guest2, $guest3, $staff]);

        // Seed Responses
        $this->command->info("\n[4/4] Seeding Responses...");
        $this->seedResponses($reports, [$staff, $staff2]);

        $this->command->info("\n==========================================");
        $this->command->info('✓ Database Seeding Completed Successfully!');
        $this->command->info('==========================================');
        $this->command->info("\nLogin Credentials:");
        $this->command->info("GUEST: guest@gmail.com / password");
        $this->command->info("STAFF: staff@gmail.com / password");
        $this->command->info("HEAD_STAFF: headstaff@gmail.com / password");
        $this->command->info("==========================================\n");
    }

    /**
     * Seed reports dengan gambar placeholder SVG
     */
    private function seedReports($guest, $guest2, $guest3)
    {
        $reportsData = [
            [
                'user_id' => $guest->id,
                'type' => 'KEJAHATAN',
                'province' => 'DKI Jakarta',
                'regency' => 'Jakarta Selatan',
                'subdistrict' => 'Kebayoran Baru',
                'village' => 'Gunung',
                'description' => 'Terjadi pencurian motor di parkiran Mall Blok M pada malam hari. Pelaku tidak terlihat jelas di CCTV karena menggunakan helm fullface dan jaket hitam. Kerugian ditaksir sekitar 25 juta rupiah. Warga sekitar diminta untuk lebih waspada dan meningkatkan keamanan di area parkir.',
                'viewers' => 145,
                'voting' => [$guest2->id, $guest3->id],
            ],
            [
                'user_id' => $guest->id,
                'type' => 'PEMBANGUNAN',
                'province' => 'Jawa Barat',
                'regency' => 'Bandung',
                'subdistrict' => 'Coblong',
                'village' => 'Dago',
                'description' => 'Jalan Dago mengalami kerusakan parah sepanjang 500 meter. Banyak lubang besar yang membahayakan pengendara motor. Sudah dilaporkan sejak 3 bulan lalu namun belum ada tindak lanjut dari dinas terkait. Beberapa kecelakaan kecil sudah terjadi akibat kondisi jalan yang buruk ini.',
                'viewers' => 320,
                'voting' => [$guest2->id, $guest3->id, $guest->id],
            ],
            [
                'user_id' => $guest2->id,
                'type' => 'SOSIAL',
                'province' => 'Jawa Timur',
                'regency' => 'Surabaya',
                'subdistrict' => 'Gubeng',
                'village' => 'Airlangga',
                'description' => 'Banjir melanda kawasan perumahan Airlangga akibat luapan sungai. Ketinggian air mencapai 50-80 cm dan menggenangi puluhan rumah warga. Warga membutuhkan bantuan logistik berupa makanan, air bersih, dan obat-obatan. Tim SAR dan relawan sudah diturunkan untuk evakuasi warga yang terdampak.',
                'viewers' => 567,
                'voting' => [$guest->id, $guest3->id],
            ],
            [
                'user_id' => $guest2->id,
                'type' => 'SOSIAL',
                'province' => 'Jawa Tengah',
                'regency' => 'Semarang',
                'subdistrict' => 'Tembalang',
                'village' => 'Sendangmulyo',
                'description' => 'Sampah menumpuk di TPA Sendangmulyo sudah tidak tertangani dengan baik. Bau menyengat mengganggu warga sekitar hingga radius 2 km. Lalat dan tikus berkembang biak dengan cepat. Warga meminta pemerintah segera menambah armada pengangkut sampah dan memperbaiki sistem pengelolaan TPA.',
                'viewers' => 234,
                'voting' => [$guest->id],
            ],
            [
                'user_id' => $guest3->id,
                'type' => 'PEMBANGUNAN',
                'province' => 'Bali',
                'regency' => 'Denpasar',
                'subdistrict' => 'Denpasar Selatan',
                'village' => 'Sanur',
                'description' => 'Lampu penerangan jalan di sepanjang pantai Sanur banyak yang mati. Kondisi ini membuat area pantai menjadi gelap dan rawan tindak kejahatan pada malam hari. Wisatawan mengeluhkan kurangnya penerangan untuk aktivitas malam. Dinas PUPR diminta segera memperbaiki lampu-lampu yang rusak.',
                'viewers' => 89,
                'voting' => [$guest->id, $guest2->id],
            ],
        ];

        $createdReports = [];

        foreach ($reportsData as $index => $reportData) {
            // Buat placeholder image
            $imageName = $this->createPlaceholderImage($reportData['type'], $index);

            $this->command->info("  → Creating report " . ($index + 1) . "/5...");

            // Create report
            $report = Report::create([
                'user_id' => $reportData['user_id'],
                'type' => $reportData['type'],
                'province' => $reportData['province'],
                'regency' => $reportData['regency'],
                'subdistrict' => $reportData['subdistrict'],
                'village' => $reportData['village'],
                'description' => $reportData['description'],
                'voting' => json_encode($reportData['voting']),
                'viewers' => $reportData['viewers'],
                'image' => $imageName,
                'statement' => true,
            ]);

            $createdReports[] = $report;

            $this->command->info("    ✓ Report created: {$reportData['type']} - {$reportData['village']}");
        }

        $this->command->info("✓ Created " . count($createdReports) . " reports");

        return $createdReports;
    }

    /**
     * Seed Comments
     */
    private function seedComments($reports, $users)
    {
        $commentsData = [
            // Comments untuk Report 1 (Pencurian Motor)
            [
                'report' => $reports[0],
                'comments' => [
                    ['user' => $users[1], 'text' => 'Semoga cepat tertangkap pelakunya. Kejahatan seperti ini harus diberantas!'],
                    ['user' => $users[2], 'text' => 'Di daerah saya juga sering terjadi pencurian motor. Memang perlu pengawasan lebih ketat.'],
                    ['user' => $users[3], 'text' => 'Sebagai staff keamanan, kami akan menindaklanjuti laporan ini. Terima kasih informasinya.'],
                ]
            ],
            // Comments untuk Report 2 (Jalan Rusak)
            [
                'report' => $reports[1],
                'comments' => [
                    ['user' => $users[0], 'text' => 'Jalan Dago memang parah banget. Kemarin ban motor saya kempes gara-gara lubang besar.'],
                    ['user' => $users[1], 'text' => 'Sudah 3 bulan tidak ada perbaikan. Kapan Dinas PU turun tangan?'],
                    ['user' => $users[2], 'text' => 'Saya juga hampir terjatuh karena lubang ini. Bahaya untuk pengendara!'],
                    ['user' => $users[3], 'text' => 'Tim kami akan segera melakukan survey lokasi. Update progress akan kami share disini.'],
                ]
            ],
            // Comments untuk Report 3 (Banjir)
            [
                'report' => $reports[2],
                'comments' => [
                    ['user' => $users[0], 'text' => 'Semoga warga yang terdampak segera mendapat bantuan. Tetap semangat!'],
                    ['user' => $users[2], 'text' => 'Donasi bisa disalurkan kemana ya? Ingin membantu warga yang terkena banjir.'],
                ]
            ],
            // Comments untuk Report 4 (Sampah TPA)
            [
                'report' => $reports[3],
                'comments' => [
                    ['user' => $users[0], 'text' => 'Baunya sampai ke rumah saya yang jaraknya 1 km. Tolong segera ditangani!'],
                    ['user' => $users[1], 'text' => 'Anak-anak mulai sakit-sakitan karena lingkungan yang tidak sehat ini.'],
                    ['user' => $users[3], 'text' => 'Dinas Lingkungan Hidup sudah kami hubungi. Mohon kesabarannya.'],
                ]
            ],
            // Comments untuk Report 5 (Lampu Jalan)
            [
                'report' => $reports[4],
                'comments' => [
                    ['user' => $users[0], 'text' => 'Wisatawan juga komplain tentang lampu yang mati. Kasihan usaha di sekitar pantai jadi sepi.'],
                    ['user' => $users[1], 'text' => 'Kemarin malam ada penjambretan di area gelap ini. Tolong diperbaiki segera!'],
                ]
            ],
        ];

        $totalComments = 0;
        foreach ($commentsData as $data) {
            foreach ($data['comments'] as $commentData) {
                Comment::create([
                    'report_id' => $data['report']->id,
                    'user_id' => $commentData['user']->id,
                    'comment' => $commentData['text'],
                    'created_at' => now()->subDays(rand(1, 7)),
                ]);
                $totalComments++;
            }
        }

        $this->command->info("✓ Created {$totalComments} comments across " . count($commentsData) . " reports");
    }

    /**
     * Seed Responses
     */
    private function seedResponses($reports, $staffs)
    {
        $responsesData = [
            // Response untuk Report 1 (Pencurian Motor) - DONE
            [
                'report' => $reports[0],
                'staff' => $staffs[0],
                'status' => 'DONE',
                'progress' => [
                    'Laporan telah diterima dan sedang dalam proses investigasi oleh pihak kepolisian.',
                    'Tim keamanan telah melakukan peninjauan CCTV di sekitar lokasi kejadian.',
                    'Pelaku berhasil diidentifikasi dan saat ini dalam tahap penangkapan.',
                    'Kasus telah selesai ditangani. Motor berhasil ditemukan dan pelaku ditangkap.',
                ]
            ],
            // Response untuk Report 2 (Jalan Rusak) - ON_PROCESS
            [
                'report' => $reports[1],
                'staff' => $staffs[1],
                'status' => 'ON_PROCESS',
                'progress' => [
                    'Laporan telah diterima oleh Dinas Pekerjaan Umum.',
                    'Tim survey telah melakukan peninjauan kondisi jalan Dago.',
                    'Proposal perbaikan jalan telah diajukan ke bagian anggaran.',
                    'Menunggu persetujuan anggaran untuk dimulainya pekerjaan perbaikan.',
                ]
            ],
            // Response untuk Report 3 (Banjir) - DONE
            [
                'report' => $reports[2],
                'staff' => $staffs[0],
                'status' => 'DONE',
                'progress' => [
                    'Tim SAR dan relawan sudah diturunkan ke lokasi.',
                    'Evakuasi warga terdampak telah dilakukan ke tempat pengungsian.',
                    'Bantuan logistik berupa makanan, air bersih, dan obat-obatan sudah didistribusikan.',
                    'Air sudah surut dan warga sudah kembali ke rumah masing-masing.',
                ]
            ],
            // Response untuk Report 4 (Sampah TPA) - ON_PROCESS
            [
                'report' => $reports[3],
                'staff' => $staffs[1],
                'status' => 'ON_PROCESS',
                'progress' => [
                    'Dinas Lingkungan Hidup sudah menerima laporan.',
                    'Penambahan 3 unit truk pengangkut sampah sedang dalam proses.',
                    'Penyemprotan disinfektan di area TPA telah dilakukan.',
                ]
            ],
        ];

        foreach ($responsesData as $data) {
            Response::create([
                'report_id' => $data['report']->id,
                'staff_id' => $data['staff']->id,
                'response_status' => $data['status'],
                'response_content' => json_encode($data['progress']),
                'created_at' => now()->subDays(rand(1, 5)),
            ]);

            $this->command->info("  → Response created for Report #{$data['report']->id} - Status: {$data['status']}");
        }

        $this->command->info("✓ Created " . count($responsesData) . " responses");
    }

    /**
     * Create placeholder image SVG
     */
    private function createPlaceholderImage($type, $index)
    {
        $colors = [
            'KEJAHATAN' => ['bg' => '220, 38, 38', 'text' => 'KEJAHATAN'],
            'PEMBANGUNAN' => ['bg' => '34, 197, 94', 'text' => 'PEMBANGUNAN'],
            'SOSIAL' => ['bg' => '249, 115, 22', 'text' => 'SOSIAL'],
        ];

        $color = $colors[$type] ?? ['bg' => '107, 114, 128', 'text' => 'PENGADUAN'];

        // Create SVG placeholder
        $svg = <<<SVG
<svg width="800" height="600" xmlns="http://www.w3.org/2000/svg">
  <rect width="800" height="600" fill="rgb({$color['bg']})"/>
  <text x="400" y="280" font-family="Arial, sans-serif" font-size="48" fill="white" text-anchor="middle" font-weight="bold">
    {$color['text']}
  </text>
  <text x="400" y="340" font-family="Arial, sans-serif" font-size="24" fill="white" text-anchor="middle" opacity="0.8">
    Laporan Pengaduan Masyarakat
  </text>
</svg>
SVG;

        $imageName = 'placeholder_' . strtolower($type) . '_' . time() . '_' . $index . '.svg';
        Storage::disk('public')->put('images/' . $imageName, $svg);

        $this->command->info("    ✓ Image created: {$imageName}");

        return $imageName;
    }
}
