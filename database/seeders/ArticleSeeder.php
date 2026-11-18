<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Poli;
use Illuminate\Database\Seeder;

/** Artikel awal supaya landing tidak kosong sebelum Gemini membuat artikel pertama. */
class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'GG' => ['5 Cara Menjaga Kesehatan Gigi & Mencegah Karang Gigi Sejak Dini', 'Karang gigi terbentuk akibat penumpukan plak yang mengeras. Ketahui langkah menyikat gigi yang tepat dan pentingnya scaling rutin 6 bulan sekali.', 'Pencegahan', 'Gigi,Mulut',
                "Karang gigi berawal dari plak, lapisan lengket berisi bakteri yang menempel setelah makan. Jika tidak dibersihkan dalam beberapa hari, plak mengeras menjadi karang gigi yang tidak bisa dihilangkan dengan sikat biasa.\n\n## Lima kebiasaan yang membantu\n- Sikat gigi dua kali sehari, pagi setelah sarapan dan malam sebelum tidur, selama sekitar dua menit.\n- Gunakan pasta gigi berfluoride dan sikat dengan gerakan melingkar yang lembut, termasuk bagian belakang gigi.\n- Bersihkan sela gigi dengan benang gigi sekali sehari.\n- Kurangi makanan dan minuman manis, terutama di antara waktu makan.\n- Lakukan pemeriksaan dan scaling rutin sekitar enam bulan sekali.\n\n## Kapan perlu ke dokter gigi\nPeriksakan diri jika gusi mudah berdarah, napas tidak sedap berkepanjangan, gigi terasa ngilu, atau muncul bercak kecokelatan pada gigi. Penanganan dini biasanya lebih sederhana dan nyaman."],
            'AN' => ['Kapan Anak Perlu Dibawa ke Dokter Spesialis Anak?', 'Demam, batuk, atau nafsu makan turun memang sering terjadi pada anak. Kenali tanda yang perlu segera diperiksa dan jadwal kontrol rutin.', 'Tips Sehat', 'Anak,Imunisasi',
                "Anak yang sakit ringan sering membaik dengan istirahat, cairan cukup, dan pemantauan di rumah. Namun ada tanda-tanda yang sebaiknya tidak ditunda untuk diperiksa.\n\n## Tanda yang perlu segera diperiksa\n- Demam lebih dari tiga hari, atau demam pada bayi di bawah tiga bulan.\n- Napas cepat, tampak sesak, atau tarikan dada yang dalam.\n- Anak sangat lemas, sulit dibangunkan, atau tidak mau minum.\n- Muntah atau diare berulang dengan tanda dehidrasi seperti jarang buang air kecil.\n- Kejang atau ruam yang menyebar cepat.\n\n## Kontrol rutin tetap penting\nJangan menunggu sakit untuk ke dokter anak. Imunisasi lengkap serta pemantauan berat badan, tinggi badan, dan tumbuh kembang membantu masalah ditemukan sejak dini. Hindari memberi obat tanpa saran dokter."],
            'OG' => ['Mengenal Pemeriksaan Kehamilan Trimester Pertama', 'Kunjungan pertama ke dokter kandungan menentukan arah kehamilan yang sehat. Ketahui pemeriksaan yang umum dilakukan dan tanda bahaya.', 'Edukasi', 'Kehamilan,Ibu Hamil',
                "Trimester pertama adalah masa pembentukan organ janin sehingga pemeriksaan sejak awal sangat dianjurkan. Datanglah ke dokter kandungan begitu kehamilan dicurigai.\n\n## Yang umumnya diperiksa\n- Tekanan darah, berat badan, dan riwayat kesehatan ibu.\n- Pemeriksaan darah sesuai anjuran dokter.\n- USG untuk memastikan lokasi dan perkembangan kehamilan.\n\n## Kebiasaan yang mendukung\n- Konsumsi makanan bergizi seimbang dan cukup minum.\n- Ikuti anjuran dokter mengenai suplemen seperti asam folat.\n- Hindari rokok dan alkohol, serta tanyakan dulu pada dokter sebelum minum obat apa pun.\n\n## Segera periksa jika\nTerjadi perdarahan, nyeri perut hebat, atau mual muntah berlebihan sehingga sulit makan dan minum."],
            'MT' => ['Mencegah Mata Lelah Akibat Layar (Computer Vision Syndrome)', 'Terlalu lama menatap layar membuat mata kering dan lelah. Terapkan kebiasaan sederhana ini untuk menjaga kenyamanan mata.', 'Pencegahan', 'Mata,Layar',
                "Saat menatap layar kita cenderung jarang berkedip sehingga mata menjadi kering, perih, dan kepala terasa berat. Kondisi ini sering disebut sindrom mata lelah digital.\n\n## Kebiasaan yang membantu\n- Terapkan aturan 20-20-20: tiap 20 menit, lihat objek sejauh 20 kaki (sekitar 6 meter) selama 20 detik.\n- Atur layar sedikit di bawah tinggi mata dan sejauh satu lengan.\n- Sesuaikan kecerahan layar dengan cahaya ruangan, hindari silau.\n- Ingat untuk berkedip lebih sering dan istirahatkan mata secara berkala.\n\n## Kapan perlu ke dokter mata\nPeriksakan diri jika penglihatan kabur tidak membaik setelah istirahat, mata terasa nyeri, atau muncul kilatan cahaya mendadak. Pemeriksaan mata berkala juga membantu memastikan ukuran kacamata masih sesuai."],
            'PD' => ['Pola Makan Seimbang untuk Mencegah Hipertensi dan Diabetes', 'Hipertensi dan diabetes sering tidak bergejala di awal. Atur pola makan dan aktivitas harian untuk menurunkan risikonya.', 'Pencegahan', 'Hipertensi,Diabetes',
                "Tekanan darah tinggi dan gula darah tinggi kerap tidak terasa pada tahap awal, namun dapat merusak jantung, ginjal, dan pembuluh darah dalam jangka panjang.\n\n## Langkah pencegahan\n- Kurangi garam dan makanan olahan yang tinggi natrium.\n- Batasi minuman manis dan makanan tinggi gula.\n- Perbanyak sayur, buah, dan biji-bijian utuh.\n- Bergerak aktif, misalnya jalan cepat sekitar 150 menit per minggu.\n- Jaga berat badan ideal dan hindari rokok.\n\n## Pemeriksaan berkala\nCek tekanan darah dan gula darah secara rutin, terutama jika ada riwayat keluarga. Segera berkonsultasi jika sering haus berlebihan, sering buang air kecil, pandangan kabur, atau sakit kepala hebat yang berulang."],
            'UM' => ['Pentingnya Kualitas Tidur dan Waktu Tidur yang Ideal', 'Tidur cukup bukan sekadar istirahat, tetapi bagian penting dari daya tahan tubuh. Ketahui kebiasaan tidur yang sehat.', 'Tips Sehat', 'Tidur,Gaya Hidup',
                "Orang dewasa umumnya membutuhkan sekitar 7 sampai 9 jam tidur setiap malam. Kurang tidur yang berlangsung lama berkaitan dengan menurunnya konsentrasi, daya tahan tubuh, dan suasana hati.\n\n## Kebiasaan tidur yang sehat\n- Tidur dan bangun pada jam yang konsisten, termasuk akhir pekan.\n- Jauhkan ponsel dan layar sekitar satu jam sebelum tidur.\n- Hindari kafein di sore hingga malam hari.\n- Buat kamar gelap, sejuk, dan tenang.\n- Hindari makan berat tepat sebelum tidur.\n\n## Kapan perlu ke dokter\nKonsultasikan jika sulit tidur berlangsung berminggu-minggu, mendengkur keras disertai henti napas, atau tetap mengantuk berlebihan di siang hari meski sudah tidur cukup."],
        ];

        foreach ($items as $code => [$title, $summary, $cat, $tags, $body]) {
            $poli = Poli::where('code', $code)->first();
            if (!$poli) continue;
            Article::updateOrCreate(['slug' => 'awal-' . strtolower($code)], [
                'poli_id' => $poli->id, 'title' => $title, 'summary' => $summary, 'category' => $cat,
                'tags' => $tags, 'body' => $body, 'week_key' => now()->format('o-\WW'),
                'source' => 'seed', 'published_at' => now(),
            ]);
        }
    }
}
