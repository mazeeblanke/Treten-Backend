<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource as ApiResource;
use App\Http\Resources\ResourceCollection;
use App\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //validate/check permission to see anothe author stuff
        $authorId = isset($request->authorId) ? (int) $request->authorId : null;
        $userId = isset($request->userId) ? (int) $request->userId : null;
        $categoryId = isset($request->categoryId) ? (int) $request->categoryId : null;
        $pageSize = $request->pageSize ?? 8;
        $page = $request->page ?? 1;
        $withAuthor = (int) $request->withAuthor;
        $q = $request->q ?? '';
        $sort = in_array($request->sort, ['asc', 'desc'])
            ? $request->sort
            : 'desc';

        $builder = Resource::where(function ($query) use ($q) {
            if ($q) {
                return $query
                    ->orWhere('title', 'like', '%' . $q . '%')
                    ->orWhere('summary', 'like', '%' . $q . '%');
            }
            return $query;
        });

        if (\is_null($withAuthor) && $withAuthor === 1)
        {
            $builder = $builder->with(['author']);
        }

        if ($categoryId)
        {
            $builder = $builder->whereHas('course.categories', function ($query) use ($categoryId) {
                $query->where('course_categories.id', $categoryId);
            });
        }
        
        if (
            auth()->check() && 
            (auth()->user()->isAnAdmin() || auth()->user()->isAnInstructor())
        )
        {
            $builder = $builder->whereAuthorId(auth()->user()->id);
        }
        // if ($authorId)
        // {
        //     $builder = $builder->whereAuthorId($authorId);
        // }

        if (
            auth()->check() &&
            auth()->user()->isAStudent()
        )
        {
            $builder = $builder->whereHas('course', function ($query) {
                return $query
                ->join(
                    'course_enrollments',
                    'course_enrollments.course_id',
                    'courses.id'
                )
                ->where('course_enrollments.user_id', auth()->user()->id);
            });
        }
        // if ($userId)
        // {
        //     $builder = $builder->whereHas(['course' => function ($query) {
        //         return $query->join(
        //             'course_enrollments',
        //             'course_enrollments.course_id',
        //             'courses.id'
        //         )
        //         ->where('course_enrollments.user_id', auth()->user()->id);
        //     }]);
        // }
       

        $resources = $builder
            ->orderBy('resources.created_at', $sort)
            ->paginate($pageSize, '*', 'page', $page);
            // dd($resources);

        return response()->json((new ResourceCollection($resources)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        // only pdf/doc

        $resource = Resource::store($request);

        return response()->json([
            'data' => new ApiResource($resource),
            'message' => 'Successfully created resource'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {
        //
    }
}