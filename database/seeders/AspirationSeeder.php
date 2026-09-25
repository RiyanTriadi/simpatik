<?php

namespace Database\Seeders;

use App\Models\Aspiration;
use App\Models\Category;
use Illuminate\Database\Seeder;

class AspirationSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()
            ->whereIn('name', [
                'Sarana dan Prasarana Kampus',
                'Kebersihan dan Sanitasi',
                'Teknologi Informasi dan Jaringan',
                'Keamanan dan Ketertiban',
                'Layanan Akademik dan Administrasi',
            ])
            ->pluck('id', 'name');

        $aspirations = [
            [
                'ticket_number' => 'ASP-20260925-0001',
                'category' => 'Sarana dan Prasarana Kampus',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => false,
                'subject' => 'Perlu penambahan ruang belajar bersama di setiap fakultas',
                'description' => 'Banyak mahasiswa kesulitan mencari tempat belajar saat mendekati masa ujian. Kampus dapat mempertimbangkan pemanfaatan ruang kelas yang kosong pada sore hari sebagai ruang belajar bersama dengan jadwal dan aturan penggunaan yang jelas.',
                'reporter_name' => 'Aulia Rahma',
                'reporter_phone' => '081234567801',
                'reporter_email' => 'aulia.rahma@example.test',
                'status' => Aspiration::STATUS_DITINDAKLANJUTI,
            ],
            [
                'ticket_number' => 'ASP-20260925-0002',
                'category' => 'Sarana dan Prasarana Kampus',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => false,
                'subject' => 'Pemasangan lebih banyak stopkontak di perpustakaan',
                'description' => 'Jumlah stopkontak di area baca lantai tiga belum sebanding dengan jumlah meja. Penambahan stopkontak yang aman dan memiliki pengaman arus akan membantu mahasiswa yang belajar menggunakan laptop dalam waktu lama.',
                'reporter_name' => 'Bima Prakoso',
                'reporter_phone' => '082198765401',
                'reporter_email' => 'bima.prakoso@example.test',
                'status' => Aspiration::STATUS_DIBACA,
            ],
            [
                'ticket_number' => 'ASP-20260925-0003',
                'category' => 'Teknologi Informasi dan Jaringan',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => false,
                'subject' => 'Penyediaan portal terpadu untuk seluruh layanan mahasiswa',
                'description' => 'Saat ini mahasiswa harus membuka beberapa situs untuk mengakses KRS, pembayaran, perpustakaan, dan pengajuan surat. Portal terpadu dengan satu akun akan mengurangi kebingungan dan memudahkan pemantauan status layanan.',
                'reporter_name' => 'Citra Lestari',
                'reporter_phone' => '085712340901',
                'reporter_email' => 'citra.lestari@example.test',
                'status' => Aspiration::STATUS_DITINDAKLANJUTI,
            ],
            [
                'ticket_number' => 'ASP-20260925-0004',
                'category' => 'Kebersihan dan Sanitasi',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => true,
                'subject' => 'Program pengurangan sampah plastik di kantin kampus',
                'description' => 'Kampus dapat mengurangi penggunaan plastik sekali pakai dengan menyediakan stasiun isi ulang air minum dan mendorong tenant kantin memakai wadah yang dapat digunakan kembali. Program ini dapat disertai insentif bagi mahasiswa yang membawa tumbler.',
                'reporter_name' => null,
                'reporter_phone' => null,
                'reporter_email' => null,
                'status' => Aspiration::STATUS_BARU,
            ],
            [
                'ticket_number' => 'ASP-20260925-0005',
                'category' => 'Keamanan dan Ketertiban',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => false,
                'subject' => 'Pemberlakuan jalur aman bagi pejalan kaki pada malam hari',
                'description' => 'Mahasiswa yang mengikuti kegiatan organisasi pada malam hari membutuhkan jalur pejalan kaki yang terang dan mudah dipantau. Penetapan jalur aman dari gedung kegiatan menuju gerbang utama akan meningkatkan rasa aman saat pulang.',
                'reporter_name' => 'Dimas Saputra',
                'reporter_phone' => '081356780912',
                'reporter_email' => 'dimas.saputra@example.test',
                'status' => Aspiration::STATUS_DIBACA,
            ],
            [
                'ticket_number' => 'ASP-20260925-0006',
                'category' => 'Layanan Akademik dan Administrasi',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => false,
                'subject' => 'Konsultasi akademik daring dengan dosen wali',
                'description' => 'Diharapkan tersedia jadwal konsultasi daring yang terintegrasi dengan sistem akademik. Fitur pemesanan waktu dan notifikasi akan membantu mahasiswa yang memiliki jadwal kuliah atau magang di luar kampus.',
                'reporter_name' => 'Elsa Maharani',
                'reporter_phone' => '082245678901',
                'reporter_email' => 'elsa.maharani@example.test',
                'status' => Aspiration::STATUS_DITINDAKLANJUTI,
            ],
            [
                'ticket_number' => 'ASP-20260925-0007',
                'category' => 'Sarana dan Prasarana Kampus',
                'creator_type' => Aspiration::CREATOR_EKSTERNAL,
                'is_anonymous' => false,
                'subject' => 'Penyediaan ruang laktasi yang mudah diakses',
                'description' => 'Kampus dapat menyediakan ruang laktasi yang bersih, privat, dan memiliki akses yang jelas bagi mahasiswi, dosen, serta tenaga kependidikan yang membutuhkan. Informasi lokasi ruang sebaiknya ditampilkan di peta kampus.',
                'reporter_name' => 'Fitri Handayani',
                'reporter_phone' => '081267890145',
                'reporter_email' => 'fitri.handayani@example.test',
                'status' => Aspiration::STATUS_BARU,
            ],
            [
                'ticket_number' => 'ASP-20260925-0008',
                'category' => 'Kebersihan dan Sanitasi',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => false,
                'subject' => 'Kebun kampus dijadikan area kompos dan edukasi lingkungan',
                'description' => 'Sisa makanan dari kantin dan daun kering dapat diolah menjadi kompos untuk taman kampus. Kegiatan ini juga dapat dijadikan program kerja bersama organisasi mahasiswa dan menjadi sarana edukasi lingkungan.',
                'reporter_name' => 'Galih Nugraha',
                'reporter_phone' => '085612340789',
                'reporter_email' => 'galih.nugraha@example.test',
                'status' => Aspiration::STATUS_DIBACA,
            ],
            [
                'ticket_number' => 'ASP-20260925-0009',
                'category' => 'Teknologi Informasi dan Jaringan',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => false,
                'subject' => 'Peminjaman perangkat presentasi melalui aplikasi kampus',
                'description' => 'Peminjaman proyektor, kamera, dan perlengkapan acara masih dilakukan melalui pesan pribadi dan sulit dilacak. Sistem peminjaman sederhana dengan kalender ketersediaan akan membantu mahasiswa dan organisasi merencanakan kegiatan.',
                'reporter_name' => 'Hana Pratiwi',
                'reporter_phone' => '081378901245',
                'reporter_email' => 'hana.pratiwi@example.test',
                'status' => Aspiration::STATUS_DITINDAKLANJUTI,
            ],
            [
                'ticket_number' => 'ASP-20260925-0010',
                'category' => 'Keamanan dan Ketertiban',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => true,
                'subject' => 'Penyediaan parkir sepeda yang lebih aman',
                'description' => 'Jumlah mahasiswa yang menggunakan sepeda meningkat, tetapi tempat parkir sepeda masih terbuka dan tidak memiliki rak yang memadai. Penyediaan rak sepeda di beberapa titik dengan kamera pengawas akan mendorong transportasi yang lebih ramah lingkungan.',
                'reporter_name' => null,
                'reporter_phone' => null,
                'reporter_email' => null,
                'status' => Aspiration::STATUS_BARU,
            ],
            [
                'ticket_number' => 'ASP-20260925-0011',
                'category' => 'Layanan Akademik dan Administrasi',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => false,
                'subject' => 'Kalender akademik diterbitkan lebih awal dan dalam format digital',
                'description' => 'Kalender akademik sebaiknya diterbitkan sebelum masa pengisian KRS dan tersedia dalam format kalender digital. Mahasiswa dapat menerima pengingat otomatis untuk jadwal penting seperti registrasi, ujian, dan pembayaran.',
                'reporter_name' => 'Imam Fauzan',
                'reporter_phone' => '082167890234',
                'reporter_email' => 'imam.fauzan@example.test',
                'status' => Aspiration::STATUS_DIBACA,
            ],
            [
                'ticket_number' => 'ASP-20260925-0012',
                'category' => 'Sarana dan Prasarana Kampus',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => false,
                'subject' => 'Papan informasi digital untuk agenda kampus',
                'description' => 'Papan informasi digital di area lobi dan gedung fakultas dapat menampilkan agenda seminar, jadwal layanan, serta pengumuman akademik. Pengelolaan terpusat akan membuat informasi lebih mudah ditemukan dan mengurangi poster yang menumpuk.',
                'reporter_name' => 'Jihan Permata',
                'reporter_phone' => '081289045612',
                'reporter_email' => 'jihan.permata@example.test',
                'status' => Aspiration::STATUS_BARU,
            ],
            [
                'ticket_number' => 'ASP-20260925-0013',
                'category' => 'Layanan Akademik dan Administrasi',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => false,
                'subject' => 'Klinik persiapan karier untuk mahasiswa tingkat akhir',
                'description' => 'Kampus dapat membuka klinik karier rutin yang menyediakan pemeriksaan CV, simulasi wawancara, dan informasi lowongan dari mitra industri. Layanan ini akan sangat membantu mahasiswa yang sedang mempersiapkan kelulusan.',
                'reporter_name' => 'Kurnia Sari',
                'reporter_phone' => '085734567812',
                'reporter_email' => 'kurnia.sari@example.test',
                'status' => Aspiration::STATUS_DITINDAKLANJUTI,
            ],
            [
                'ticket_number' => 'ASP-20260925-0014',
                'category' => 'Sarana dan Prasarana Kampus',
                'creator_type' => Aspiration::CREATOR_EKSTERNAL,
                'is_anonymous' => false,
                'subject' => 'Peta kampus dengan informasi aksesibilitas',
                'description' => 'Peta kampus sebaiknya mencantumkan ramp, lift, toilet aksesibel, serta jalur tanpa tangga. Informasi tersebut akan membantu pengunjung dan sivitas akademika dengan kebutuhan mobilitas merencanakan rute mereka.',
                'reporter_name' => 'Lukman Hakim',
                'reporter_phone' => '081345678902',
                'reporter_email' => 'lukman.hakim@example.test',
                'status' => Aspiration::STATUS_DIBACA,
            ],
            [
                'ticket_number' => 'ASP-20260925-0015',
                'category' => 'Layanan Akademik dan Administrasi',
                'creator_type' => Aspiration::CREATOR_INTERNAL,
                'is_anonymous' => false,
                'subject' => 'Program pendampingan kesehatan mental yang berkelanjutan',
                'description' => 'Selain layanan konseling berdasarkan janji temu, kampus dapat menyediakan sesi kelompok dan edukasi kesehatan mental secara berkala. Jadwal yang rutin dan informasi yang mudah ditemukan akan membuat mahasiswa lebih nyaman mencari bantuan sejak awal.',
                'reporter_name' => 'Maya Kusuma',
                'reporter_phone' => '082256789013',
                'reporter_email' => 'maya.kusuma@example.test',
                'status' => Aspiration::STATUS_BARU,
            ],
        ];

        foreach ($aspirations as $aspiration) {
            $categoryId = $categories->get($aspiration['category']);
            unset($aspiration['category']);

            Aspiration::updateOrCreate(
                ['ticket_number' => $aspiration['ticket_number']],
                [...$aspiration, 'category_id' => $categoryId],
            );
        }
    }
}