<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Group;

class InvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invitation::factory()
            ->for(User::where('user_name', 'ali')->first())
            ->for(Group::where('user_id', 2)->first())
            ->state(['sent_id' => '2'])
            ->create();
        Invitation::factory()
            ->for(User::where('user_name', 'jacob')->first())
            ->for(Group::where('user_id', 1)->first())
            ->state(['sent_id' => '1'])
            ->create();
        Invitation::factory()
            ->for(User::where('user_name', 'jacob')->first())
            ->for(Group::where('user_id', 3)->first())
            ->state(['sent_id' => '3'])
            ->create();
    }
}
