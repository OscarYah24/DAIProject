<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Muestra la página principal del blog
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Array de artículos de ejemplo para mostrar en el blog
        $articles = [
            [
                'id' => 1,
                'title' => 'Biodegradable Food Packaging',
                'excerpt' => 'After recognizing the need for more eco-friendly fast food practices and products, designer Luis Arrieta created biodegradable food packaging as part of a school project.',
                'category' => 'Food',
                'tag' => 'Packaging',
                'author' => 'Luis Arrieta',
                'date' => 'January 15, 2025',
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop',
                'reading_time' => '5 min read'
            ],
            [
                'id' => 2,
                'title' => 'Placeit: Create Mockups right in your Browser (now 15% off!)',
                'excerpt' => 'Creating perfect mockups has never been so easy. With Placeit\'s user-friendly online tools you can find incredible templates no matter.',
                'category' => 'Design',
                'tag' => 'Mockup',
                'author' => 'Sarah Johnson',
                'date' => 'January 10, 2025',
                'image' => 'https://images.unsplash.com/photo-1559028012-481c04fa702d?w=800&h=600&fit=crop',
                'reading_time' => '3 min read'
            ],
            [
                'id' => 3,
                'title' => 'Flatscreen TV Modifies Perception, Reduces Package Damage During Delivery',
                'excerpt' => 'For some reason, bicycles in big cardboard boxes have a tendency to get dropped, bashed or crushed by delivery companies, which has turned Dutch vanmoof into a design solution.',
                'category' => 'Technology',
                'tag' => 'Innovation',
                'author' => 'Mike Chen',
                'date' => 'January 5, 2025',
                'image' => 'https://images.unsplash.com/photo-1593784991095-a205069470b6?w=800&h=600&fit=crop',
                'reading_time' => '7 min read'
            ],
            [
                'id' => 4,
                'title' => 'The Future of Sustainable Design',
                'excerpt' => 'Exploring how designers are revolutionizing the industry with eco-friendly materials and sustainable practices that benefit both consumers and the environment.',
                'category' => 'Sustainability',
                'tag' => 'Green Design',
                'author' => 'Emma Wilson',
                'date' => 'December 28, 2024',
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&h=600&fit=crop',
                'reading_time' => '6 min read'
            ],
            [
                'id' => 5,
                'title' => 'Minimalist Web Design Trends 2025',
                'excerpt' => 'Discover the latest minimalist design trends that are shaping the web in 2025. From micro-animations to bold typography, less is definitely more.',
                'category' => 'Web Design',
                'tag' => 'Trends',
                'author' => 'Alex Rivera',
                'date' => 'December 20, 2024',
                'image' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=800&h=600&fit=crop',
                'reading_time' => '4 min read'
            ],
            [
                'id' => 6,
                'title' => 'AI in Creative Industries',
                'excerpt' => 'How artificial intelligence is transforming creative workflows, enhancing productivity, and opening new possibilities for designers and artists worldwide.',
                'category' => 'Technology',
                'tag' => 'AI',
                'author' => 'David Park',
                'date' => 'December 15, 2024',
                'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&h=600&fit=crop',
                'reading_time' => '8 min read'
            ]
        ];

        return view('blog.index', compact('articles'));
    }
}