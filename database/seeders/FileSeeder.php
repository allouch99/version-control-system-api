<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\File;
class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        File::factory()
        ->count(5)
        ->for(Group::where('name','laravel')->first())
        ->state([
            'directory' => 'files/ali/laravel/',
        ])
        ->sequence(
            ['name' => 'index.php'],
            ['name' => 'config.php'],
            ['name' => 'db.php'],
            ['name' => 'mail.php'],
            ['name' => 'test.php'],
        )
        ->create();

        File::factory()
        ->count(5)
        ->for(Group::where('name','react')->first())
        ->state([
            'directory' => 'files/ali/react/',
        ])
        ->sequence(
            ['name' => 'index.js'],
            ['name' => 'config.js'],
            ['name' => 'db.js'],
            ['name' => 'mail.js'],
            ['name' => 'test.js'],
        )
        ->create();

        File::factory()
        ->count(5)
        ->for(Group::where('name','machine-learning')->first())
        ->state([
            'directory' => 'files/jacob/machine-learning/',
        ])
        ->sequence(
            ['name' => 'index.py'],
            ['name' => 'config.py'],
            ['name' => 'test.py'],
        )
        ->create();

        File::factory()
        ->count(5)
        ->for(Group::where('name','big-data')->first())
        ->state([
            'directory' => 'files/jacob/big-data/',
        ])
        ->sequence(
            ['name' => 'data.sql'],
            ['name' => 'tables.sql'],
        )
        ->create();

        File::factory()
        ->count(5)
        ->for(Group::where('name','html')->first())
        ->state([
            'directory' => 'files/lith/html/',
        ])
        ->sequence(
            ['name' => 'index.html'],
            ['name' => 'footer.html'],
            ['name' => 'navbar.html'],
        )
        ->create();
        File::factory()
        ->count(5)
        ->for(Group::where('name','css')->first())
        ->state([
            'directory' => 'files/lith/css/',
        ])
        ->sequence(
            ['name' => 'style.css'],
            ['name' => 'normalize.css'],
            ['name' => 'bootstrap.css'],
        )
        ->create();

        File::factory()->count(100)->create();
    }
}
