<?php

namespace Database\Factories;

use App\Events\Report\CreateReport;
use App\Models\Group;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $group = Group::inRandomOrder()->first();
        $directory = $group->filesDirectory;
        $name = fake()->unique()->word().'.'.fake()->randomElement([
            'php','html','css','js','py','sql','ts'
        ]);
        Storage::makeDirectory($directory);
        return [
            'group_id' => $group->id,
            'locked_by' => null,
            'name' => $name,
            'directory' => $directory
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (File $file) {
            $contents = fake()->sentence(10) ."\n";
            $contents = $contents . $contents . $contents . $contents;
            Storage::put($file->directory . $file->name, $contents);
            event(new CreateReport($file->group->user,$file));
        });
    }
}
