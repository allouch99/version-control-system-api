<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Group;
use App\Models\Membership;
class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Membership::factory()
            ->for(User::where('user_name', 'ali')->first())
            ->for(Group::find(4))
            ->state(['role' => 'writer'])
            ->create();
        Membership::factory()
            ->for(User::where('user_name', 'ali')->first())
            ->for(Group::find(6))
            ->state(['role' => 'viewer'])
            ->create();
    }
}
