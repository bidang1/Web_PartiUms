<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            // Kategori: Pendaftaran
            [
                'category' => 'Pendaftaran',
                'question' => 'Bagaimana cara mendaftar sub-acara / lomba di PARTI?',
                'answer' => 'Anda dapat mendaftar melalui halaman detail sub-acara terkait pada website ini, kemudian mengklik tombol "Daftar Sekarang" untuk mengisi formulir pendaftaran resmi.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'category' => 'Pendaftaran',
                'question' => 'Apakah pendaftaran terbuka untuk umum atau khusus mahasiswa UMS?',
                'answer' => 'Beberapa sub-acara terbuka untuk umum (pelajar/mahasiswa nasional), dan beberapa sub-acara khusus untuk internal mahasiswa UMS. Silakan cek syarat pada halaman masing-masing sub-acara.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'category' => 'Pendaftaran',
                'question' => 'Apakah ada biaya pendaftaran untuk mengikuti acara ini?',
                'answer' => 'Informasi biaya pendaftaran berbeda-beda untuk setiap sub-acara. Sebagian acara bersifat gratis dan sebagian memiliki biaya pendaftaran terjangkau.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'category' => 'Pendaftaran',
                'question' => 'Apakah seluruh peserta akan mendapatkan e-sertifikat?',
                'answer' => 'Ya, seluruh peserta yang terdaftar resmi dan mengikuti rangkaian acara hingga selesai akan mendapatkan e-sertifikat resmi dari HIMATIF UMS.',
                'order' => 4,
                'is_active' => true,
            ],

            // Kategori: Pelaksanaan Acara
            [
                'category' => 'Pelaksanaan Acara',
                'question' => 'Apakah rangkaian acara PARTI dilaksanakan secara online atau offline?',
                'answer' => 'Pelaksanaan acara bersifat hybrid (kombinasi online dan offline). Tahap penyisihan kompetisi umumnya online, sedangkan acara puncak dilaksanakan secara offline di kampus UMS.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'category' => 'Pelaksanaan Acara',
                'question' => 'Di mana lokasi pelaksanaan acara puncak PARTI?',
                'answer' => 'Acara puncak akan dilaksanakan di Kompleks Kampus 2 / Kampus 3 Universitas Muhammadiyah Surakarta. Detail gedung dan ruangan akan diinformasikan menjelang hari H.',
                'order' => 2,
                'is_active' => true,
            ],

            // Kategori: Kompetisi & Panduan
            [
                'category' => 'Kompetisi & Panduan',
                'question' => 'Di mana saya bisa mengunduh Rulebook / Buku Panduan Lomba?',
                'answer' => 'Rulebook dapat diunduh langsung melalui tombol "Unduh Rulebook" pada halaman detail sub-acara yang Anda ikuti.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'category' => 'Kompetisi & Panduan',
                'question' => 'Apakah peserta boleh mendaftar lebih dari satu sub-acara?',
                'answer' => 'Boleh, selama jadwal pelaksanaan antar sub-acara tidak bentrok dan peserta memenuhi persyaratan masing-masing perlombaan.',
                'order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
