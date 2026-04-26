<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Biaya Produksi/Cost Revenue',
            'description' => 'Biaya yang terkait langsung dengan produksi barang atau jasa, seperti bahan baku, tenaga kerja langsung, dan overhead pabrik.',
        ]);

        Category::create([
            'name' => 'Biaya Operasional',
            'description' => 'Biaya rutin untuk menjalankan operasional sehari-hari perusahaan, seperti listrik, air, internet, dan pemeliharaan.',
        ]);

        Category::create([
            'name' => 'Biaya Administrasi dan Perpajakan',
            'description' => 'Biaya untuk keperluan administrasi, pembuatan dokumen, serta kewajiban perpajakan seperti PPN, PPh, dan lain-lain.',
        ]);

        Category::create([
            'name' => 'Biaya Pemasaran',
            'description' => 'Biaya untuk promosi, iklan, event, dan aktivitas pemasaran lainnya.',
        ]);

        Category::create([
            'name' => 'Lainnya',
            'description' => 'Kategori untuk biaya-biaya yang tidak termasuk dalam kategori di atas.',
        ]);
    }
}
