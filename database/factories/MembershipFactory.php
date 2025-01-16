<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Membership>
 */
class MembershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        $group = Group::inRandomOrder()->first();
        $users = DB::table('seed_memberships')
            ->select('user_id')->where('group_id',$group->id)
            ->get();
       
        $invalid_ids = $users->pluck('user_id');
        $user = User::whereNotIn('id', $invalid_ids)->inRandomOrder()->first();
        DB::table('seed_memberships')->insert([
            'user_id' => $user->id,
            'group_id' => $group->id
        ]);


        return [
            'user_id' => $user->id,
            'group_id' => $group->id,
            'role' => fake()->randomElement(['viewer','writer']),
        ];
        
    }
 
    
}
