<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //fake()->randomElement(User::pluck('id')),
        // $user = User::inRandomOrder()->first();
        // $group_name = fake()->unique()->slug(2);
        // $path = 'images/'.$user['user_name'].'/'. $group_name;
        // $bg_image = fake()->imageUrl(640,480);
        // $bg_image = Http::get($bg_image);
        // $bg_image_url = Storage::put($path, $bg_image);
        
        return [
            'user_id' => User::inRandomOrder()->first(),
            'name' => fake()->unique()->slug(2),
            'description' => fake()->sentence(10),
            'bg_image_url' => null,
            'icon_image_url' => null,
            'type' => fake()->randomElement(['public','private'])
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Group $group) {
            $user = User::find($group->user_id);
            Storage::makeDirectory('images/'.$user->user_name.'/'. $group->name);
            Storage::makeDirectory('files/'.$user->user_name.'/'. $group->name);
            if($group['bg_image_url']!= null)
                Storage::copy('test/'.$group->bgImageOriginalUrl, $group->bgImageOriginalUrl);
            if($group['icon_image_url']!= null)
                Storage::copy('test/'.$group->iconImageOriginalUrl, $group->iconImageOriginalUrl);

        });
    }
}
