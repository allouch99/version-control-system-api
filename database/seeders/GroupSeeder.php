<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\User;
class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Group::factory()
                ->count(2)
                ->for(User::where('user_name','ali')->first())
                ->sequence([
                    'name' => 'laravel',
                    'description' => 'Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation — freeing you to create without sweating the small things.',
                    'bg_image_url' => 'images/ali/laravel/bg.JPG',
                    'icon_image_url' => 'images/ali/laravel/icon.JPG',
                    'type' => 'public'
                ],
                [
                    'name' => 'react',
                    'description' => 'React lets you build user interfaces out of individual pieces called components. Create your own React components like Thumbnail, LikeButton, and Video. Then combine them into entire screens, pages, and apps',
                    'bg_image_url' => 'images/ali/react/bg.JPG',
                    'icon_image_url' => 'images/ali/react/icon.JPG',
                    'type' => 'private'
                ]
                )
                ->create();

                Group::factory()
                ->count(2)
                ->for(User::where('user_name','jacob')->first())
                ->sequence([
                    'name' => 'big-data',
                    'description' => 'Big data describes large and diverse datasets that are huge in volume and also rapidly grow in size over time. Big data is used in machine learning',
                    'bg_image_url' => 'images/jacob/big-data/bg.JPG',
                    'icon_image_url' => 'images/jacob/big-data/icon.JPG',
                    'type' => 'public'
                ],
                [
                    'name' => 'machine-learning',
                    'description' => 'Machine learning is a subset of AI, which uses algorithms that learn from data to make predictions. These predictions can be generated through supervised learning, where algorithms learn patterns from existing data',
                    'bg_image_url' => 'images/jacob/machine-learning/bg.JPG',
                    'icon_image_url' => 'images/jacob/machine-learning/icon.JPG',
                    'type' => 'private'
                ]
                )
                ->create();

                Group::factory()
                ->count(2)
                ->for(User::where('user_name','lith')->first())
                ->sequence([
                    'name' => 'html',
                    'description' => 'Hypertext Markup Language (HTML) is the standard markup language for documents designed to be displayed in a web browser.',
                    'bg_image_url' => 'images/lith/html/bg.JPG',
                    'icon_image_url' => 'images/lith/html/icon.JPG',
                    'type' => 'public'
                ],
                [
                    'name' => 'css',
                    'description' => 'Cascading Style Sheets (CSS) is a style sheet language used for specifying the presentation and styling of a document written in a markup language such as HTML',
                    'bg_image_url' => 'images/lith/css/bg.JPG',
                    'icon_image_url' => 'images/lith/css/icon.JPG',
                    'type' => 'public'
                ]
                )
                ->create();
    }
}
