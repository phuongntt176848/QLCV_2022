<?php

namespace Database\Seeders;

use App\Models\Tag as ModelsTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Tag extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = ['Task', 'Bug', 'Story'];

        foreach ($tags as $tag) {
            ModelsTag::create([
                'name' => $tag
            ]);
        }
    }
}
