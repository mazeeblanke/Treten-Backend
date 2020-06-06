<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\Setting;
use App\Resource;
use App\Transaction;
use App\CourseEnrollment;
use App\CourseBatchAuthor;
use Illuminate\Http\Request;
use App\Filters\CourseCollectionFilters;
use App\Http\Resources\CourseCollection;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\GetCourseRequest;
use App\Http\Resources\Course as CourseResource;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @author mazino ukah <ewomaukah@yahoo.com>
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GetCourseRequest $request, CourseCollectionFilters $filters)
    {
        return response()->json(
            new CourseCollection(
                Course::with(['author'])
                    ->filterUsing($filters)
                    ->orderByLatest()
                    ->paginate(
                        $request->pageSize ?? 8,
                        '*',
                        'page',
                        $request->page ?? 1
                    )
            )
        );
    }

    public function popular(Request $request)
    {

        $popularCoursesSetting = Setting::whereSettingName('popularCourses')
            ->select(['setting_value'])
            ->first();

        $popularCourseIds = unserialize($popularCoursesSetting->setting_value);

        $courses = Course::whereIn('id', array_values(json_decode($popularCourseIds, true)))->get();

        return response()->json(new CourseCollection($courses));
    }

    public function groupByCategories()
    {
        $courses = cache()->remember("courses.categories.group", now()->addMinutes(15), function () {
            return Course::mainCategories()
                ->whereIsPublished(1)
                ->hasInstructors()
                ->get()
                ->groupBy(function ($course) {
                    return $course->categories()->first()->name;
                })
                ->map(function($courseGroup, $category) {
                    return [
                        "value" => $category,
                        "label" => $category,
                        "children" => $courseGroup->map(function($course) {
                            return [
                                "value" => aurl("/courses/{$course->slug}", config('app.frontend_url')),
                                "label" => $course->title,
                            ];
                        })
                    ];
                })
                ->values();
        });

        return response()->json([
            'message' => 'Successfully fetched course groups',
            'data' => $courses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCourseRequest $request)
    {
        $course = Course::store($request);

        return response()->json([
            'data' => new CourseResource($course),
            'message' => 'Successfully fetched course',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $course)
    {
        $courseSlugSegments = explode('-', $course);
        $courseId = (int) $courseSlugSegments[count($courseSlugSegments) - 1];
        $enrolled = (int) $request->enrolled ?? 0;
        // array_pop($courseSlugSegments);
        // $courseTitle = \implode(" ", $courseSlugSegments);
        // dd($courseId);

        if (!Course::whereId($courseId)->exists())
        {
            return response()->json([
               'message' => 'Unable to find the requested course'
            ], 404);
        }

        $builder= Course::with([
            'author',
            'coursePath',
        ]);

        if ($enrolled === 1)
        {
            if (!auth()->check()) {
                return response()->json([
                    'message' => 'Must be logged in!'
                ], 422);
            }

            if (auth()->user()->role != 'student') {
                return response()->json([
                    'message' => 'Must be a student!'
                ], 422);
            }

            $studentIsEnrolled = CourseEnrollment::whereUserId(auth()->user()->id)
                ->whereCourseId($courseId)
                ->whereActive(1)
                ->first();

            if (!$studentIsEnrolled) {
                return response()->json([
                    'message' => 'You have not enrolled or this course'
                ], 422);
            }

            $builder = $builder->with([
                'resources',
                'courseReviews' => function ($query) {
                    return $query->whereEnrolleeId(auth()->user()->id);
                },
                'instructorReviews' => function ($query) {
                    return $query->whereEnrolleeId(auth()->user()->id);
                },
                'timetable' => function ($query) {
                    return $query
                        ->join(
                            'course_enrollments',
                            'course_batch_author.course_batch_id',
                            'course_enrollments.course_batch_id'
                        )
                        ->join(
                            'users',
                            'users.id',
                            'course_batch_author.author_id'
                        )
                        ->join(
                            'course_batches',
                            'course_batches.id',
                            'course_batch_author.course_batch_id'
                        )
                        ->join(
                            'instructors',
                            'instructors.id',
                            'users.userable_id'
                        )
                        ->where('course_enrollments.user_id', auth()->user()->id)
                        ->where('users.userable_type', '!=', null);
                }
            ]);
        }

        if ($enrolled !== 1)
        {
            $builder = $builder->with([
                'courseReviews' => function ($query) {
                    return $query->with('enrollee')->whereApproved(1);
                },
                'instructors' => function($query) {
                    return $query->with('userable')->where(function ($query) {
                        $query->orWhere('timetable', '!=', 'a:0:{}')->orWhere('timetable', '!=', null);
                    })->where('users.userable_type', '!=', null); },
                'batches' => function ($query) {
                    return $query
                        ->where(function ($query) {
                            if (auth()->check() && auth()->user()->isAnInstructor()) {
                                return $query->where('course_batch_author.author_id', auth()->user()->id);
                            }
                            if (auth()->check() && auth()->user()->isAStudent()) {

                                // TODO: include check to ensure that only batches that are not filled up show up here
                                return $query->join(
                                    'course_batch_author',
                                    'course_batch_author.course_batch_id',
                                    'course_batches.id'
                                )
                                // ->where('course_batch_author.timetable', '!=', 'a:0:{}')
                                ->where('course_batch_author.timetable', '!=', null);
                            }
                            if (auth()->check() && auth()->user()->isAnAdmin()) {
                                return $query->where('course_batch_author.author_id', auth()->user()->id);
                            }
                            return $query;
                        })
                        ->orderBy('course_batches.id', 'desc');
                    // return $query->join(
                    //     'course_batch_instructor',
                    //     'course_batch_instructor.course_batch_id',
                    //     '=',
                    //     'course_batches.id'
                    // )
                    // ->where('course_batch_instructor.instructor_id', auth()->user()->userable_id)
                    // ->orderBy('course_batches.id', 'desc');
                }
            ]);
        }

        $course =  $builder->where('courses.id', $courseId)
            // ->whereTitle($courseTitle)
            ->first();

        if (auth()->check() && auth()->user()->isAStudent()) {
            // return details of user transaction in the database (regardless of status) if loggedin
            // if not loggedin, check the session for deatils relating to the current course

            // rework this, fetch only availbale batches i.e join the enrollment table and, group by modes of delivery and the available dates should be nested
            $enrollment = CourseEnrollment::with('batch')->whereUserId(auth()->user()->id)
                ->whereCourseId($course->id)
                ->first();

            if ($enrollment) {
                $transaction = Transaction::whereCourseId($enrollment->course_id)
                    ->whereUserId(auth()->user()->id)
                    ->whereStatus('success')
                    ->first();

                if ($transaction && session()->has('enrollments.'.$course->id))
                {
                    session()->forget('enrollments.'.$course->id);
                }
                $course->transaction = $transaction;
                $course->enrollment = [
                    'batchId' => $enrollment->course_batch_id ,
                    'expiresAt' => $enrollment->expires_at ,
                    'courseId' => $enrollment->course_id,
                    'active' => $enrollment->active,
                    'plan' => $enrollment->batch->mode_of_delivery,
                    'availableDate' => $enrollment->batch->start_date,
                    'transactionId' => optional($transaction)->transaction_id,
                    'invoiceId' =>  optional($transaction)->invoice_id,
                    'amount' =>  optional($transaction)->amount,
                    'price' =>  $enrollment->batch->price,
                ];
            } else if (session()->has('enrollments.'.$course->id)) {
                $course->enrollment = session()->get('enrollments.'.$course->id);
            } else {
                $course->enrollment = [];
            }
        }

        if ($enrolled !== 1)
        {
            $course->availableModesOfDelivery = $course
                ->batches
                ->map(function ($batch) {
                    return [
                        'batchId' => $batch->id,
                        'price' => $batch->price,
                        'courseId' => $batch->course_id,
                        'batchName' => $batch->batch_name,
                        'modeOfDelivery' => $batch->mode_of_delivery,
                        'startDate' => \Carbon\Carbon::parse($batch->start_date)->format('Y-m-d'),
                        'friendlyStartDate' => \Carbon\Carbon::parse($batch->start_date)->format('d M Y'),
                    ];
                })
                ->unique(function ($batch) {
                    return $batch['batchId'];
                })
                ->groupBy("modeOfDelivery");
        }

        if (!auth()->check() && session()->has('enrollments.'.$course->id))
        {
            $course->enrollment = session()->get('enrollments.'.$course->id);
        }

        // print_r($course);

        return response()->json([
            'message' => 'Successfully fetched course',
            'data' => new CourseResource($course),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $course = $course->modify($request);
        return response()->json([
            'message' => 'Successfully fetched course',
            'data' => new CourseResource($course)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
        $deleted = $course->delete();
        if ($deleted) {
            return response()->json([
                'message' => 'Successfully deleted course'
            ]);
        }

        return response()->json([
            'message' => 'An error occured'
        ]);
    }
}
