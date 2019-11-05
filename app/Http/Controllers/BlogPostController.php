<?php

namespace App\Http\Controllers;

use App\BlogPost;
use App\Http\Requests\CreateBlogPostRequest;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->pageSize ?? 6;
        $page = $request->page ?? 1;
        $blogPosts = BlogPost::with('author')
            ->wherePublished(1)
            ->orderBy('published_at', 'desc')
            ->paginate($pageSize, '*', 'page', $page);

        return response()->json(array_merge([
            'message' => 'Successfully fetched blog posts',
        ], $blogPosts->toArray()));
    }

    public function latestBlogPosts()
    {
        $blogPosts = BlogPost::with('author')
            ->wherePublished(1)
            ->orderBy('published_at', 'desc')
            ->take(2)
            ->get();

        return response()->json([
            'message' => 'Successfully fetched blog posts',
            'blogPosts' => $blogPosts 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBlogPostRequest $request)
    {
        // store image file
        $request->blog_image->store('blog_images');

        // create the post
        $blog = BlogPost::create([
            'blog_image' => $request->blog_image,
            'body' => $request->body,
            'title' => $request->title,
            'author_id' => $request->author_id,
        ]);

        // filter and create separate arrays of tags and tag ids
        $tagIds = array_filter($request->tags, function ($tag) {
            return is_numeric($tag);
        });

        $tags = array_filter($request->tags, function ($tag) {
            return is_string($tag) && !is_numeric($tag);
        });

        $tags = array_map(function ($tag) {
            return [
                'name' => $tag,
            ];
        }, $tags);

        // associate tag ids
        $blog->tags()->attach($tagIds);

        // createMany tags on post
        $blog->tags()->createMany($tags);

        // return response
        return response()->json([
            'message' => 'Successfully created Blog posts',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function show(BlogPost $blogPost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function edit(BlogPost $blogPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlogPost $blogPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogPost $blogPost)
    {
        //
    }
}
