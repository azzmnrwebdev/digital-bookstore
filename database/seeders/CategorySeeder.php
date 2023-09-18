<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Pendidikan Dasar',
                'slug' => 'pendidikan-dasar',
                'description' => 'Mencakup pendidikan pra-sekolah hingga sekolah dasar.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Pendidikan Menengah',
                'slug' => 'pendidikan-menengah',
                'description' => 'Membahas pendidikan di sekolah menengah pertama dan sekolah menengah atas.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Pendidikan Tinggi',
                'slug' => 'pendidikan-tinggi',
                'description' => 'Berfokus pada perguruan tinggi dan universitas.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Pendidikan Khusus',
                'slug' => 'pendidikan-khusus',
                'description' => 'Membahas pendidikan bagi siswa dengan kebutuhan khusus.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Teknologi Pendidikan',
                'slug' => 'teknologi-pendidikan',
                'description' => 'Mencakup penerapan teknologi dalam pendidikan.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Kepemimpinan Pendidikan',
                'slug' => 'kepemimpinan-pendidikan',
                'description' => 'Membahas kepemimpinan dalam konteks pendidikan.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Evaluasi dan Penilaian',
                'slug' => 'evaluasi-dan-penilaian',
                'description' => 'Berfokus pada evaluasi dan penilaian dalam pendidikan.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Pendidikan Multikultural',
                'slug' => 'pendidikan-multikultural',
                'description' => 'Membahas pendidikan dalam konteks keberagaman budaya dan sosial.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Psikologi Pendidikan',
                'slug' => 'psikologi-pendidikan',
                'description' => 'Membahas aspek psikologis dalam pendidikan.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Pendidikan Anak Usia Dini',
                'slug' => 'pendidikan-anak-usia-dini',
                'description' => 'Mencakup pendidikan untuk anak usia dini sebelum masuk sekolah.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Pendidikan Karakter',
                'slug' => 'pendidikan-karakter',
                'description' => 'Membahas pengembangan karakter siswa.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Pendidikan STEM',
                'slug' => 'pendidikan-stem',
                'description' => 'Mencakup sains, teknologi, rekayasa, dan matematika dalam pendidikan.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Pendidikan Profesional',
                'slug' => 'pendidikan-profesional',
                'description' => 'Membahas pengembangan profesional guru dan staf pendidikan.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ],
            [
                'name' => 'Pendidikan Inklusif',
                'slug' => 'pendidikan-inklusif',
                'description' => 'Membahas pendidikan inklusif bagi semua siswa.',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ]
        ];

        Category::insert($categories);
    }
}
