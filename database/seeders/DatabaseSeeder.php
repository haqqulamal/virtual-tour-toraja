<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Scene;
use App\Models\Artifact;
use App\Models\Hotspot;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate([
            'email' => 'admin@toraja.test',
        ], [
            'name' => 'Admin Toraja',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create categories
        $categoryTradisi = Category::firstOrCreate([
            'slug' => 'tradisi-budaya',
        ], [
            'name' => 'Tradisi & Budaya',
            'description' => 'Tradisi dan budaya Toraja yang masih dilestarikan hingga kini',
        ]);

        $categoryBangunan = Category::firstOrCreate([
            'slug' => 'bangunan-tradisional',
        ], [
            'name' => 'Bangunan Tradisional',
            'description' => 'Arsitektur dan bangunan khas Toraja',
        ]);

        $categoryUpacara = Category::firstOrCreate([
            'slug' => 'upacara-ritual',
        ], [
            'name' => 'Upacara & Ritual',
            'description' => 'Upacara ritual adat Toraja',
        ]);

        // Create scenes (locations in Baruppu', North Toraja)
        $sceneLembang = Scene::firstOrCreate([
            'title' => 'Lembang Baruppu\'',
        ], [
            'description' => 'Pemandangan indah Lembang Baruppu\' dengan rumah-rumah tradisional Toraja yang megah',
            'panorama_image' => 'lembang_baruppu.jpg',
            'location' => 'Baruppu\', Utara Toraja',
            'latitude' => -2.7833,
            'longitude' => 119.9167,
            'is_published' => true,
            'order' => 1,
        ]);

        $sceneAlang = Scene::firstOrCreate([
            'title' => 'Liang Alang',
        ], [
            'description' => 'Makam batu kuno yang merupakan peninggalan nenek moyang Toraja',
            'panorama_image' => 'liang_alang.jpg',
            'location' => 'Liang Alang, Baruppu\'',
            'latitude' => -2.7867,
            'longitude' => 119.9200,
            'is_published' => true,
            'order' => 2,
        ]);

        $sceneManene = Scene::firstOrCreate([
            'title' => 'Makam Ma\'nene',
        ], [
            'description' => 'Lokasi perayaan ritual Ma\'nene (membersihkan dan mengecat ulang jenazah)',
            'panorama_image' => 'makam_manene.jpg',
            'location' => 'Makam Tradisional Baruppu\'',
            'latitude' => -2.7900,
            'longitude' => 119.9250,
            'is_published' => true,
            'order' => 3,
        ]);

        // Create hotspots for scene 1
        Hotspot::firstOrCreate([
            'scene_id' => $sceneLembang->id,
            'title' => 'Info: Rumah Toraja',
        ], [
            'description' => 'Rumah tradisional Toraja dengan atap berbentuk perahu (tongkonan)',
            'pitch' => 0,
            'yaw' => 0,
            'type' => 'info',
            'artifact_id' => null,
        ]);

        // Navigation hotspot to next scene
        Hotspot::firstOrCreate([
            'scene_id' => $sceneLembang->id,
            'title' => 'Kunjungi: Liang Alang →',
        ], [
            'description' => 'Navigasi ke lokasi makam bersejarah',
            'pitch' => 10,
            'yaw' => 90,
            'type' => 'scene',
            'linked_scene_id' => $sceneAlang->id,
        ]);

        // Create artifacts
        $artifactTongkonan = Artifact::firstOrCreate([
            'name' => 'Tongkonan (Rumah Tradisional)',
        ], [
            'category_id' => $categoryBangunan->id,
            'description' => 'Rumah adat Toraja yang memiliki bentuk unik menyerupai perahu dengan atap melengkung ke atas',
            'cultural_significance' => 'Tongkonan adalah simbol identitas Toraja dan menunjukkan status sosial keluarga. Dibangun tanpa menggunakan paku, menggunakan sistem sambungan tradisional yang sangat erat.',
            'image' => 'tongkonan.jpg',
            'keywords' => 'rumah,tradisional,atap,perahu,arsitektur',
            'is_published' => true,
        ]);

        $artifactManene = Artifact::firstOrCreate([
            'name' => 'Ma\'nene (Ritual Pembersihan Jenazah)',
        ], [
            'category_id' => $categoryUpacara->id,
            'description' => 'Upacara ritual Toraja yang diselenggarakan sekali dalam setahun untuk membersihkan dan mengecat ulang jenazah nenek moyang',
            'cultural_significance' => 'Ma\'nene adalah manifestasi dari kepercayaan Toraja bahwa orang yang sudah meninggal masih merupakan bagian dari keluarga. Jenazah diperlakukan seolah-olah masih hidup, dibersihkan, diberi makan, dan diajak jalan-jalan keliling kampung.',
            'image' => 'manene.jpg',
            'keywords' => 'upacara,ritual,jenazah,tradisi,kematian',
            'is_published' => true,
        ]);

        $artifactKris = Artifact::firstOrCreate([
            'name' => 'Keris Toraja',
        ], [
            'category_id' => $categoryTradisi->id,
            'description' => 'Senjata tradisional Toraja yang memiliki keindahan dan nilai seni tinggi. Gagang keris biasanya terbuat dari tanduk kerbau dengan ukiran yang rumit.',
            'cultural_significance' => 'Keris Toraja merupakan simbol kehormatan dan kekuatan laki-laki Toraja. Setiap keris memiliki cerita dan makna tersendiri berkaitan dengan sejarah keluarga.',
            'image' => 'keris_toraja.jpg',
            'keywords' => 'senjata,keris,tradisi,tanduk,kerajinan',
            'is_published' => true,
        ]);

        $artifactLipa = Artifact::firstOrCreate([
            'name' => 'Li\'pa\' (Sarung Tradisional)',
        ], [
            'category_id' => $categoryTradisi->id,
            'description' => 'Kain sarung tradisional Toraja dengan motif khas yang ditenun tangan. Menggunakan pewarna alami dari alam sekitar.',
            'cultural_significance' => 'Li\'pa\' merupakan pakaian adat yang wajib dikenakan dalam setiap upacara adat Toraja. Motif dan warna tertentu menunjukkan status dan jenis upacara yang sedang berlangsung.',
            'image' => 'lipa.jpg',
            'keywords' => 'pakaian,sarung,tenun,motif,tradisional',
            'is_published' => true,
        ]);

        $artifactSigalagala = Artifact::firstOrCreate([
            'name' => 'Si Galagala (Patung Penjaga Makam)',
        ], [
            'category_id' => $categoryBangunan->id,
            'description' => 'Patung kayu tradisional Toraja yang ditempatkan di sekitar makam sebagai penjaga dan simbol kehadiran arwah leluhur',
            'cultural_significance' => 'Si Galagala mewakili roh penghuni makam. Pahatan wajah dan postur tubuh Si Galagala mencerminkan karakteristik dan profesi orang yang dimakamkan.',
            'image' => 'sigalagala.jpg',
            'keywords' => 'patung,makam,penjaga,kayu,seni',
            'is_published' => true,
        ]);

        // Create hotspots linking to artifacts
        Hotspot::firstOrCreate([
            'scene_id' => $sceneManene->id,
            'title' => 'Baca: Ma\'nene Ritual',
        ], [
            'description' => 'Pelajari lebih lanjut tentang ritual unik Ma\'nene',
            'pitch' => -10,
            'yaw' => 180,
            'type' => 'artifact',
            'artifact_id' => $artifactManene->id,
        ]);

        Hotspot::firstOrCreate([
            'scene_id' => $sceneLembang->id,
            'title' => 'Info: Keris Toraja',
        ], [
            'description' => 'Senjata tradisional Toraja dengan nilai sejarah tinggi',
            'pitch' => 5,
            'yaw' => 270,
            'type' => 'artifact',
            'artifact_id' => $artifactKris->id,
        ]);
    }
}
