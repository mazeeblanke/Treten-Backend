<?php

namespace App\Http\Controllers;

use App\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Filters\CertificationCollectionFilters;
use App\Http\Resources\CertificationCollection;

class CertificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CertificationCollectionFilters $filters)
    {
        return response()->json(
            new CertificationCollection(
                Certification::filterUsing($filters)
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
            'company' => 'required',
            'bannerImage' => 'required|file'
        ]);

         // store image file
        $path = $request->bannerImage->store('certifications');

         // create the post
        Certification::create([
            'banner_image' => $path,
            'company' => $request->company,
            // 'author_id' => $request->user()->id,
        ]);

        return [
            'message' => 'Successfully created'
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Certification  $certification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Certification $certification)
    {
        $this->validate($request, [
            'company' => 'required',
            'bannerImage' => 'required'
        ]);

        if ($request->hasFile('bannerImage')) {
            // store image file
            $path = $request->bannerImage->store('certifications');
            Storage::delete($certification->banner_image);
            $certification->banner_image = $path;
        }

        $certification->update([
            'company' => $request->company
        ]);

        return [
            'message' => 'Successfully updated!',
            'model' => $certification->fresh()
        ];
    }

    /**
     * get form fields
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function formFields(Request $request, Certification $certification)
    {
        return [
            'matrix' => [
                'company' => [
                    'type' => 'text',
                    'placeholder' => 'Enter company'
                ],
                'bannerImage' => [
                    'type' => 'image'
                ]
            ],
            'model' => isset($certification) ? $certification->toArray() + [
                'bannerImage' => $certification->banner_image ? Storage::url($certification->banner_image) : null
            ] : null,
            'endpoints' => [
                'createUrl' => route('certifications.store', [], false),
                'updateUrl' => route('certifications.update', [
                    'certification' => $certification->id
                ], false)
            ]
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Certification  $certification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Certification $certification)
    {
        $certification->forceDelete();
        return response()->json([
            'message' => 'Succesfully deleted',
        ]);
    }
}
