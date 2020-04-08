<?php

namespace App\Http\Controllers;

use App\User;
use App\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\BecomeAnInstructorMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserCollection;
use App\Http\Resources\User as UserResource;

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
        $courseId= $request->courseId;
        $builder = User::with('details')
            ->where('userable_type', 'App\Instructor')
            ->where(function ($builder) use ($q) {
                if ($q) {
                    return $builder
                        ->orWhere('first_name', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%')
                        ->orWhere('last_name', 'like', '%' . $q . '%')
                        ->orWhere('phone_number', 'like', '%' . $q . '%')
                        ->orWhere('status', 'like', '%' . $q . '%');
                }
                return $builder;
            });

        if ($showActive) {
            $builder = $builder->whereStatus('active');
        }

        if (\is_numeric($courseId))
        {
            $builder = $builder->join('course_batch_author', 'course_batch_author.author_id', 'users.id')
            ->where('course_batch_author.course_id', $courseId)
            ->select(\DB::raw('count(course_batch_id) as total_batches, users.*'))
            ->groupBy('users.id');
        }

        $instructors = $builder
            ->orderBy('users.created_at', 'desc')
            ->paginate($pageSize, '*', 'page', $page);

        return response()->json((new UserCollection($instructors))->additional([
            'message' => 'Successfully fetched instructors'
        ]));

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
        $instructor = User::whereHasMorph('userable', Instructor::class, function ($query) use ($instructorId) {
            return $query->whereId((int) $instructorId);
        })->with('userable')->first();

        if (!$instructor) {
            return response()->json([
                'message' => 'Unable to find the requested instructor',
            ], 422);
        }

        return response()->json((new UserResource($instructor))->additional([
            'message' => 'Successfully fetched instructor',
        ]));

        // return response()->json([
        //     'message' => 'Successfully fetched instructor',
        //     'instructor' => $instructor,
        // ]);
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
        $instructor->update([
            'bio' => $request->bio,
            'certifications' => $request->certifications,
            'consideration' => $request->consideration,
            'education' => $request->education,
            'qualifications' => $request->qualifications,
            'social_links' => $request->socialLinks,
            'title' => $request->title,
            'work_experience' => $request->workExperience
        ]);
        return response()->json([
            "message" => "Successfully updated instructor",
            "instructor" => $instructor->find($instructor->id),
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
        $instructor->forceDelete();
        return response()->json([
            'message' => 'Succesfully deleted',
        ]);
    }

    public function becomeAnInstructor(Request $request) {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'qualifications' => 'required',
            'consideration' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'file' => 'required|file',
        ]);

        \Mail::to(config('mail.to'))
            ->send(new BecomeAnInstructorMail($request->all()));
    }
}
