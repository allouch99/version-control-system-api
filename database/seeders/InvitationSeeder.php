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
            ->for(Group::find(4))
            ->state([
                'sent_id' => '2',
                'recipient_id'=> '1',
                'status' => 'accepted'
                ])
            ->create();
        Invitation::factory()
            ->for(Group::find(6))
            ->state([
                'sent_id' => '3',
                'recipient_id'=> '1',
                'status' => 'accepted'
                ])
            ->create();
        Invitation::factory()
            ->for(Group::find(2))
            ->state([
                'sent_id' => '1',
                'recipient_id'=> '2'
                ])
            ->create();

    }
}
