<?php

namespace Tests\Feature;

use App\Tag;
use App\BlogPost;
use App\Instructor;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogPostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanCreateABlog()
    {
        $tags = factory(Tag::class, 3)->create();

        Instructor::unsetEventDispatcher();
        $instructor = factory(Instructor::class)->create();

        $response = $this
            ->actingAs($instructor->details)
            ->json(
                'POST',
                '/api/blog-posts',
                [
                    'title' => 'A simple test',
                    'body' => 'A simple test body text',
                    'author_id' => $instructor->details->id,
                    'tags' => ['mac', 'joke', $tags[0]->id, $tags[1]->id, $tags[2]->id],
                    'blog_image' => UploadedFile::fake()->image('test.png')
                ]
            );

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'A simple test',
            'body' => 'A simple test body text',
            'author_id' => $instructor->details->id,
        ]);

        $this->assertDatabaseHas('tags', [
            'name' => 'mac'
        ]);

        $this->assertDatabaseHas('tags', [
            'name' => $tags[1]->name
        ]);

        $response->assertSee('Successfully created Blog posts');

        $response->assertStatus(200);
    }


    public function testCannotCreateABlogIfNotLoggedIn()
    {
        Instructor::unsetEventDispatcher();
        $instructor = factory(Instructor::class)->create();

        $response = $this    
            ->json(
                'POST',
                '/api/blog-posts',
                [
                    'title' => 'A simple test',
                    'body' => 'A simple test body text',
                    'author_id' => $instructor->details->id,
                    'tags' => ['mac'],
                    'blog_image' => UploadedFile::fake()->image('test.png')
                ]
            );

        $this->assertDatabaseMissing('blog_posts', [
            'title' => 'A simple test',
            'body' => 'A simple test body text',
            'author_id' => $instructor->details->id,
        ]);

        $this->assertDatabaseMissing('tags', [
            'name' => 'mac'
        ]);

        $response->assertStatus(403);
    }

    public function testCreateABlogValidationRules()
    {

        Instructor::unsetEventDispatcher();
        $instructor = factory(Instructor::class)->create();

        $response = $this
            ->actingAs($instructor->details)    
            ->json(
                'POST',
                '/api/blog-posts',
                []
            );
        
        $response->assertJsonFragment([
            'errors' => [
                'body' => ['The body field is required.'],
                "author_id" => ["The author id field is required."],
                "blog_image" => ["The blog image field is required."],
                "title" => ["The title field is required."]
            ]
        ]);    
        
        $response->assertStatus(422);
    }

    public function testViewBlogPageByPage()
    {
        $firstBlogPost = factory(BlogPost::class)->create();
        $secondBlogPost = factory(BlogPost::class)->create();
        $thirdBlogPost = factory(BlogPost::class)->create();
        $fourthBlogPost = factory(BlogPost::class)->create();


        $page = 2;
        $pageSize= 2;

        $response = $this   
            ->json(
                'GET',
                "/api/blog-posts?page=$page&pageSize=$pageSize"
            );   
        
        $response->assertJsonFragment([
            'current_page' => $page,
        ]);

        $response->assertSee($fourthBlogPost->title);
        $response->assertSee($thirdBlogPost->title);
        $response->assertSee('Successfully fetched blog posts');

        $response->assertStatus(200);
    }


    public function testViewLatestBlogPost()
    {
        $firstBlogPost = factory(BlogPost::class)->create([
            'published_at' => \Carbon\Carbon::now()->addDay()
        ]);
        $secondBlogPost = factory(BlogPost::class)->create([
            'published_at' => \Carbon\Carbon::now()->addDays(2)
        ]);
        $thirdBlogPost = factory(BlogPost::class)->create([
            'published_at' => \Carbon\Carbon::now()->addDays(3)
        ]);
        $fourthBlogPost = factory(BlogPost::class)->create([
            'published_at' => \Carbon\Carbon::now()->addDays(4)
        ]);
        $fifthBlogPost = factory(BlogPost::class)->create([
            'published_at' => \Carbon\Carbon::now()->addDays(5)
        ]);
        $sixthBlogPost = factory(BlogPost::class)->create([
            'published_at' => \Carbon\Carbon::now()->addDays(6)
        ]);
        $seventhBlogPost = factory(BlogPost::class)->create([
            'published_at' => \Carbon\Carbon::now()->addDays(7)
        ]);
        $eighthBlogPost = factory(BlogPost::class)->create([
            'published_at' => \Carbon\Carbon::now()->addDays(8)
        ]);
        $ninethBlogPost = factory(BlogPost::class)->create([
            'published_at' => \Carbon\Carbon::now()->addDays(9)
        ]);
        $tenthBlogPost = factory(BlogPost::class)->create([
            'published_at' => \Carbon\Carbon::now()->addDays(10)
        ]);

        $response = $this   
            ->json(
                'GET',
                "/api/latest-blog-posts"
            );   
        

        $response->assertJsonCount(2, 'blogPosts');
        $response->assertSee($tenthBlogPost->title);
        $response->assertSee($ninethBlogPost->title);
        $response->assertDontSee($firstBlogPost->title);
        $response->assertSee('Successfully fetched blog posts');

        $response->assertStatus(200);
    }

}
