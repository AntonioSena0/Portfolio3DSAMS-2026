<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $ids = \Illuminate\Support\Facades\DB::table('categories')->pluck('id', 'slug');

        if ($ids->isEmpty()) {
            $this->call(CategorySeeder::class);
            $ids = \Illuminate\Support\Facades\DB::table('categories')->pluck('id', 'slug');
        }

        $products = [
            ['category' => 'eletronicos', 'name' => 'Smart TV 50" 4K UHD', 'slug' => 'smart-tv-50-4k-uhd', 'price' => 2499.90, 'stock' => 25],
            ['category' => 'eletronicos', 'name' => 'Tablet 10.1" 128GB Wi-Fi', 'slug' => 'tablet-101-128gb-wifi', 'price' => 1299.00, 'stock' => 40],
            ['category' => 'eletronicos', 'name' => 'Drone com Câmera HD', 'slug' => 'drone-com-camera-hd', 'price' => 1899.90, 'stock' => 12],
            ['category' => 'informatica', 'name' => 'Notebook i5 8GB 512GB SSD', 'slug' => 'notebook-i5-8gb-512gb-ssd', 'price' => 3499.00, 'stock' => 18],
            ['category' => 'informatica', 'name' => 'Teclado Mecânico RGB ABNT2', 'slug' => 'teclado-mecanico-rgb-abnt2', 'price' => 349.90, 'stock' => 60],
            ['category' => 'informatica', 'name' => 'Mouse Gamer 7200 DPI', 'slug' => 'mouse-gamer-7200-dpi', 'price' => 149.90, 'stock' => 85],
            ['category' => 'informatica', 'name' => 'SSD NVMe 1TB', 'slug' => 'ssd-nvme-1tb', 'price' => 499.90, 'stock' => 50],
            ['category' => 'smartphones', 'name' => 'Smartphone 256GB 8GB RAM', 'slug' => 'smartphone-256gb-8gb-ram', 'price' => 2199.00, 'stock' => 30],
            ['category' => 'smartphones', 'name' => 'Capinha Anti-impacto Universal', 'slug' => 'capinha-anti-impacto-universal', 'price' => 49.90, 'stock' => 200],
            ['category' => 'smartphones', 'name' => 'Película 3D + Aplicador', 'slug' => 'pelicula-3d-com-aplicador', 'price' => 29.90, 'stock' => 300],
            ['category' => 'eletrodomesticos', 'name' => 'Air Fryer 5L Digital', 'slug' => 'air-fryer-5l-digital', 'price' => 549.90, 'stock' => 35],
            ['category' => 'eletrodomesticos', 'name' => 'Aspirador Robô com Mapeamento', 'slug' => 'aspirador-robo-com-mapeamento', 'price' => 1799.00, 'stock' => 15],
            ['category' => 'eletrodomesticos', 'name' => 'Cafeteira Espresso 19 Bar', 'slug' => 'cafeteira-espresso-19-bar', 'price' => 899.90, 'stock' => 22],
            ['category' => 'games', 'name' => 'Controle Sem Fio Pro', 'slug' => 'controle-sem-fio-pro', 'price' => 399.90, 'stock' => 45],
            ['category' => 'games', 'name' => 'Cadeira Gamer Ergonômica', 'slug' => 'cadeira-gamer-ergonomica', 'price' => 1299.90, 'stock' => 10],
            ['category' => 'games', 'name' => 'Headset Gamer 7.1 USB', 'slug' => 'headset-gamer-71-usb', 'price' => 299.90, 'stock' => 55],
            ['category' => 'audio-e-video', 'name' => 'Fone Bluetooth com ANC', 'slug' => 'fone-bluetooth-com-anc', 'price' => 599.90, 'stock' => 70],
            ['category' => 'audio-e-video', 'name' => 'Caixa de Som Portátil 30W', 'slug' => 'caixa-de-som-portatil-30w', 'price' => 349.00, 'stock' => 48],
            ['category' => 'audio-e-video', 'name' => 'Monitor 27" QHD 165Hz', 'slug' => 'monitor-27-qhd-165hz', 'price' => 1899.00, 'stock' => 20],
            ['category' => 'casa-inteligente', 'name' => 'Lâmpada Smart Wi-Fi RGB (2 un.)', 'slug' => 'lampada-smart-wifi-rgb-2un', 'price' => 129.90, 'stock' => 120],
            ['category' => 'casa-inteligente', 'name' => 'Plug Inteligente Wi-Fi 10A', 'slug' => 'plug-inteligente-wifi-10a', 'price' => 79.90, 'stock' => 150],
            ['category' => 'casa-inteligente', 'name' => 'Assistente Virtual com Tela 8"', 'slug' => 'assistente-virtual-com-tela-8', 'price' => 749.00, 'stock' => 28],
            ['category' => 'acessorios', 'name' => 'Cabo USB-C 100W 2m Nylon', 'slug' => 'cabo-usb-c-100w-2m-nylon', 'price' => 59.90, 'stock' => 250],
            ['category' => 'acessorios', 'name' => 'Hub USB-C 7 em 1 HDMI 4K', 'slug' => 'hub-usb-c-7-em-1-hdmi-4k', 'price' => 249.90, 'stock' => 65],
            ['category' => 'acessorios', 'name' => 'Mochila Antifurto para Notebook 15.6"', 'slug' => 'mochila-antifurto-notebook-156', 'price' => 199.90, 'stock' => 80],
        ];

        $rows = array_map(fn (array $p) => [
            'category_id' => $ids[$p['category']],
            'name' => $p['name'],
            'slug' => $p['slug'],
            'description' => "Produto {$p['name']} da linha {$p['category']}.",
            'price' => $p['price'],
            'stock' => $p['stock'],
            'active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ], $products);

        \Illuminate\Support\Facades\DB::table('products')->upsert(
            $rows,
            ['slug'],
            ['category_id', 'name', 'description', 'price', 'stock', 'active', 'updated_at']
        );

        \App\Models\Product::factory(25)->create();
    }
}
