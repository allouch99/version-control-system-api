<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['rejected','unread']);
        $group = Group::inRandomOrder()->first();
        $sent = $group->user;
        $recipient_ids = $group->invitations->pluck('recipient_id');
        $recipient = User::whereNot('id',$sent->id)
                        ->whereNotIn('id', $recipient_ids)
                        ->inRandomOrder()->first();
        $membership =DB::table('seed_memberships')->select('user_id')
                ->where('user_id',$recipient->id)
                ->where('group_id',$group->id)->first();
        if($membership){
            $status = 'accepted';
        }
       
        return [
            'recipient_id' => $recipient->id,
            'sent_id' => $sent->id,
            'group_id' => $group->id,
            'role' => fake()->randomElement(['viewer','writer']),
            'status' => $status,
            'description' => fake()->sentence(15),
        ];
    }
}
