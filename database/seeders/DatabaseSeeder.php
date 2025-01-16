<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::deleteDirectory('/files');
        Storage::deleteDirectory('/images');
        DB::statement('drop table if exists seed_memberships');
        DB::statement('create table if not exists seed_memberships( user_id int not null, group_id int not null)');

        $this->call([
            UserSeeder::class,
            GroupSeeder::class,
            FileSeeder::class,
            MembershipSeeder::class,
            InvitationSeeder::class,
        ]);

        DB::statement('drop table if exists seed_memberships');

    }
}
