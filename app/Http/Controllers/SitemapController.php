<?php

namespace App\Http\Controllers;

use App\Course;
use App\BlogPost;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sitemap = app("sitemap");

        // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
        // by default cache is disabled
        $sitemap->setCache('laravel.sitemap', 60);

        $now = Carbon::now();

        $frontendUrl = config('app.frontend_url');

        $pages = [
            route('home', [], false),
            '/policies',
            '/contact-us',
            '/about-us',
            '/termsandconditions',
            '/resources/interview-questions'
        ];

        foreach($pages as $page) {
            $sitemap->add($frontendUrl.$page, $now, '1.0', 'always');
        }

        // Courses
        $courses = Course::published()->get();
        foreach ($courses as $course) {
            $url = "/courses/{$course->slug}";
            $sitemap->add($frontendUrl.$url, $course->updated_at, '0.5', 'daily');
        }

        // Blogs
        $blogPosts = BlogPost::published()->get();
        foreach ($blogPosts as $blogPost) {
          $url = "/blog/{$blogPost->blog_slug}";
          $sitemap->add($frontendUrl.$url, $blogPost->updated_at, '0.5', 'daily');
        }

        // // Instructors
        // $publications = Publication::published()->get();
        // foreach ($publications as $publication) {
        //     $url = route('livingwithtnbc.publications.show', $publication->slug);
        //     $sitemap->add($url, $publication->updated_at, '0.5', 'daily');
        // }

        // // Course Paths
        // $stories = Story::published()->get();
        // foreach ($stories as $story) {
        //     $url = route('livingwithtnbc.stories.show', $story->slug);
        //     $sitemap->add($url, $story->updated_at, '0.5', 'daily');
        // }

        return $sitemap->render('xml');
    }
}
