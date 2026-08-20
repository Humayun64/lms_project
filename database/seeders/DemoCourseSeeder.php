<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoCourseSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', Str::slug('Kolom Digital School'))->first();

        $course = Course::firstOrCreate(
            ['slug' => 'professional-digital-marketing-demo'],
            [
                'category_id'       => $category?->id,
                'title'             => 'Professional Digital Marketing (Demo)',
                'type'              => 'online',
                'level'             => 'beginner',
                'language'          => 'Bangla / English',
                'duration'          => '3-4 Months',
                'short_description' => 'A complete demo course showing sections, lessons, and a quiz.',
                'description'       => 'This is a sample course auto-created so you can see how a fully built course looks: sections, video and text lessons, free previews, and a quiz with questions.',
                'outcome'           => 'Digital Marketing Executive / SEO Specialist / Freelancer',
                'certificate'       => true,
                'status'            => 'published',
            ]
        );

        // Only build the curriculum once (skip if it already has sections)
        if ($course->sections()->count() > 0) {
            return;
        }

        // Section 1: Fundamentals
        $s1 = $course->sections()->create(['title' => 'Digital Marketing Fundamentals', 'order' => 1]);
        $s1->lessons()->create([
            'title' => 'Welcome & Introduction', 'type' => 'video',
            'video_source' => 'link', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration' => '05:00', 'is_preview' => true, 'order' => 1,
        ]);
        $s1->lessons()->create([
            'title' => 'What is Digital Marketing?', 'type' => 'text',
            'content' => 'Digital marketing is the promotion of products or services using digital channels such as search engines, social media, email, and websites.',
            'duration' => '08:00', 'order' => 2,
        ]);

        // Section 2: SEO
        $s2 = $course->sections()->create(['title' => 'Search Engine Optimization (SEO)', 'order' => 2]);
        $s2->lessons()->create([
            'title' => 'SEO Basics', 'type' => 'video',
            'video_source' => 'link', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration' => '12:00', 'order' => 1,
        ]);

        // A quiz lesson with questions
        $quiz = $s2->lessons()->create([
            'title' => 'SEO Quiz', 'type' => 'quiz', 'pass_mark' => 60, 'order' => 2,
        ]);

        $q1 = $quiz->questions()->create(['question' => 'What does SEO stand for?', 'order' => 1]);
        $q1->options()->createMany([
            ['option_text' => 'Search Engine Optimization', 'is_correct' => true],
            ['option_text' => 'Social Engine Options', 'is_correct' => false],
            ['option_text' => 'Search Easy Online', 'is_correct' => false],
        ]);

        $q2 = $quiz->questions()->create(['question' => 'Which is an on-page SEO factor?', 'order' => 2]);
        $q2->options()->createMany([
            ['option_text' => 'Title tags & headings', 'is_correct' => true],
            ['option_text' => 'The weather', 'is_correct' => false],
            ['option_text' => 'Your phone brand', 'is_correct' => false],
        ]);
    }
}
