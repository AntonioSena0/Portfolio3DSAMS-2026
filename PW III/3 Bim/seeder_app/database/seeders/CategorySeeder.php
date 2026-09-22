<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $categories = [
            ['name' => 'Eletrônicos', 'slug' => 'eletronicos', 'description' => 'Dispositivos, acessórios e gadgets.'],
            ['name' => 'Informática', 'slug' => 'informatica', 'description' => 'Hardware, periféricos e componentes para PC.'],
            ['name' => 'Smartphones', 'slug' => 'smartphones', 'description' => 'Celulares, capinhas e acessórios mobile.'],
            ['name' => 'Eletrodomésticos', 'slug' => 'eletrodomesticos', 'description' => 'Utilidades para cozinha, limpeza e lar.'],
            ['name' => 'Games', 'slug' => 'games', 'description' => 'Consoles, jogos, controles e cadeiras gamer.'],
            ['name' => 'Áudio e Vídeo', 'slug' => 'audio-e-video', 'description' => 'Fones, caixas de som, TVs e monitores.'],
            ['name' => 'Casa Inteligente', 'slug' => 'casa-inteligente', 'description' => 'Automação residencial: lâmpadas, plugs e assistentes.'],
            ['name' => 'Acessórios', 'slug' => 'acessorios', 'description' => 'Cabos, adaptadores, mochilas e suportes.'],
        ];

        $rows = array_map(fn (array $c) => [...$c, 'active' => true, 'created_at' => $now, 'updated_at' => $now], $categories);

        \Illuminate\Support\Facades\DB::table('categories')->upsert(
            $rows,
            ['slug'],
            ['name', 'description', 'active', 'updated_at']
        );
    }
}
