<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Tag as TagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPost extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'blogImage' => $this->blog_image,
            'blogSlug' => $this->blog_slug,
            'body' => $this->body,
            'title' => $this->title,
            'publishedAt' => $this->published_at,
            'friendlyPublishedAt' => $this->friendly_published_at,
            'contentSummary' => $this->content_summary,
            'author' => new UserResource($this->whenLoaded('author')),
            'tags' => new TagResource($this->whenLoaded('tags')),
            'authorId' => $this->author_id,
        ];
    }
}
