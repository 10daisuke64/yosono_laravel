<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        
        DB::table('categories')->insert([
            [
                'id' => '1',
                'name' => '木工',
                'slug' => 'woodwork',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '2',
                'name' => '家具',
                'slug' => 'furniture',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '3',
                'name' => 'リノベ',
                'slug' => 'renovation',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
        
        DB::table('posts')->insert([
            [
                'id' => '1',
                'user_id' => '1',
                'main_image' => 'kVjRBquCxJJqKtlpoAeqWQtWGgny2ftk00GzuaZ8.jpg',
                'title' => 'hoge',
                'body' => 'hogehoge',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
        
    }
}
