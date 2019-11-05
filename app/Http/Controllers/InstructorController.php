<?php

namespace App\Http\Controllers;

use App\User;
use App\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InstructorController extends Controller
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
        $q = $request->q ?? '';
        $showActive = $request->showActive ?? 0;
        $builder = User::with('details')
            ->where('userable_type', 'App\Instructor')
            ->where(function ($builder) use($q) {
                if ($q) {
                    return $builder
                        ->orWhere('first_name', 'like', '%' .$q. '%')
                        ->orWhere('email', 'like', '%' .$q. '%')
                        ->orWhere('last_name', 'like', '%' .$q. '%')
                        ->orWhere('phone_number', 'like', '%' .$q. '%')
                        ->orWhere('status', 'like', '%' .$q. '%');
                }
                return $builder;
            });

        if ($showActive) {
            $builder = $builder->whereStatus('active');
        }  
        
        $instructors = $builder
            ->latest()
            ->paginate($pageSize, '*', 'page', $page);

        return response()->json(array_merge([
            'message' => 'Successfully fetched instructors',
        ], $instructors->toArray()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function show($instructor_slug)
    {
      $instructorSlugSegments = explode('_', $instructor_slug);   
      $instructorId = $instructorSlugSegments[count($instructorSlugSegments) - 1];

    //   $instructor = Instructor::find((int)$instructorId)->load('details');
      $instructor = User::whereHasMorph('userable', Instructor::class,  function ($query) use ($instructorId) {
        return $query->whereId((int) $instructorId);
      })->with('userable')->first();

      if (!$instructor)
      {
        return response()->json([
            'message' => 'Unable to find the requested instructor'
        ], 422);
      }

      return response()->json([
          'message' => 'Successfully fetched instructor',
          'instructor' => $instructor
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instructor $instructor)
    {
        $instructor->update($request->all());
        return response()->json([
            "message" => "Successfully updated instructor",
            "instructor" => $instructor->find($instructor->id)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instructor $instructor)
    {
        //
    }
}
