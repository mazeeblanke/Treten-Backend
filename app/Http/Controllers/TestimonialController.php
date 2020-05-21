<?php

namespace App\Http\Controllers;

use App\Filters\TestimonialCollectionFilters;
use App\Testimonial;
use Illuminate\Http\Request;
use App\Http\Resources\TestimonialCollection;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request, TestimonialCollectionFilters $filters)
    {
        return response()->json(
            new TestimonialCollection(
                Testimonial::filterUsing($filters)
                    ->latest()
                    ->paginate(
                        $request->pageSize ?? 8,
                        '*',
                        'page',
                        $request->page ?? 1
                    )
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'text' => 'required',
            'role' => 'required',
            'name' => 'required'
        ]);

        Testimonial::create($request->all() + [
            'profile_pic' => '/static/images/student.png',
        ]);

        return [
            'message' => 'Successfully created'
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function edit(Testimonial $testimonial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $testimonial->update($request->all() + [
            'profile_pic' => '/static/images/student.png',
        ]);

        return [
            'message' => 'Successfully updated!',
            'model' => $testimonial->fresh()
        ];
    }

    /**
     * get form fields
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function formFields(Request $request, Testimonial $testimonial)
    {
        return [
            'matrix' => [
                'name' => [
                    'type' => 'text',
                    'placeholder' => 'Enter name'
                ],
                'role' => [
                    'type' => 'text',
                    'placeholder' => 'Enter role'
                ],
                'text' => [
                    'type' => 'textarea',
                    'placeholder' => 'Enter testimonial'
                ],
            ],
            'model' => isset($testimonial) ? $testimonial : null,
            'endpoints' => [
                'createUrl' => route('testimonials.store', [], false),
                'updateUrl' => route('testimonials.update', [
                    'testimonial' => $testimonial->id
                ], false)
            ]
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->forceDelete();
        return response()->json([
            'message' => 'Succesfully deleted',
        ]);
    }
}
