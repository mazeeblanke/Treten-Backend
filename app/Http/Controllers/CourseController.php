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
use App\Http\Resources\CourseCollection;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Resources\Course as CourseResource;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //validate notassigned, must be 1 or 0
        //validate withBatches, must be 1 or 0
        //validate ninstructor, must be numeric and also check that the requester has the right permission to access the information
        //if instructor id then use permissins,
        $pageSize = $request->pageSize ?? 8;
        $page = $request->page ?? 1;
        $q = $request->q ?? '';
        $isPublished = isset($request->isPublished) ? (int) $request->isPublished : null;
        $enrolled = isset($request->enrolled) ? (int) $request->enrolled : null;
        $category = $request->category;
        $courseId = (int) $request->courseId;
        $authorId = $request->authorId;
        $categoryId = isset($request->categoryId) ? (int) $request->categoryId : null;
        $minimal = isset($request->minimal) ? (int) $request->minimal : null;
        $notAssigned = isset($request->notAssigned) ? (int) $request->notAssigned :  null;
        $hasInstructor = isset($request->hasInstructor) ? (int) $request->hasInstructor :  null;
        $sort = in_array($request->sort, ['asc', 'desc'])
            ? $request->sort
            : 'desc';

        $builder = Course::with(['author'])->where(function ($query) use ($q) {
            if ($q) {
                return $query->where('courses.title', 'like', '%' . $q . '%');
            }
            return $query;
        });

        if ($isPublished === 0 || $isPublished === 1) {
            $builder = $builder->whereIsPublished($isPublished);
        }

        if (!is_null($category) && $category !== 'all' )
        {
            $builder = $builder->whereHas('categories', function($query) use ($category){
                return $query->where('course_categories.name', $category);
            });
        }

        if (!is_null($categoryId))
        {
            $builder = $builder->whereHas('categories', function($query) use ($categoryId){
                return $query->where('course_categories.id', $categoryId);
            });
        }

        if (!\is_null($hasInstructor))
        {
            $builder = $builder->with(['instructors' => function($query) {
                return $query->where(function ($query) {
                    $query->orWhere('timetable', '!=', 'a:0:{}')->orWhere('timetable', '!=', null);
                })->where('users.userable_type', '!=', null);
            }])->whereHas('instructors', function ($query) {
                return $query->where(function ($query) {
                    $query->orWhere('timetable', '!=', 'a:0:{}')->orWhere('timetable', '!=', null);
                })->where('users.userable_type', '!=', null);
            });
        }
 
        if (!\is_null($authorId)) 
        {
            // dd(is_numeric($authorId));
            if (!is_numeric($authorId)) {
                $names = collect(explode(' ', $authorId))->filter(function ($q) {
                    return $q;
                })
                ->values()
                ->toArray();
                $authorId = User::where(function ($q) use ($names) {
                    $q->orWhere('first_name', $names[0] ?? null)
                        ->orWhere('last_name', $names[1] ?? null)
                        ->orWhere('first_name', $names[1] ?? null)
                      ->orWhere('last_name', $names[0] ?? null);
                })->first();
            }
            if (!$authorId) {
                return response()->json([
                    'message' => 'Unable to find user specified',
                    'data' => []
                ]);
            } else {
                $authorId = $authorId->id;
            }
            $sub = Course::select(
                \DB::raw('max(course_batch_author.id) as cba_id'),
                    'courses.id', 
                    'courses.title',
                    'courses.created_at',
                    'courses.banner_image',
                    'course_batch_author.course_id'
                )
                ->join(
                    'course_batch_author', 
                    'course_batch_author.course_id',`
                    'courses.id'
                );

                if (!\is_null($notAssigned)) 
                {
                    // $action = '=';
                    if ($notAssigned === 1) {
                        // $action = '!=';
                        $ids = CourseBatchAuthor::where('author_id', $authorId)
                            ->get()
                            ->pluck('course_id')
                            ->toArray();
                        $sub = $sub->groupBy(
                            'course_batch_author.course_id'
                        )->whereNotIn('course_batch_author.course_id', $ids);
                    } 
                    if ($notAssigned === 0) {
                        $sub = $sub->groupBy(
                            'course_batch_author.author_id',
                            'course_batch_author.course_id'
                        )->orderBy('cba_id', $sort);
                        $sub = $sub->where('course_batch_author.author_id', $authorId);
                    } 
                } else {
                    $sub = $sub->groupBy('course_batch_author.course_id')
                    ->where('course_batch_author.author_id', $authorId);
                }  
                
                // $sub->dd();
                // dd($sub->get()->pluck('course_id'));
            $courseIds = $sub->get()->pluck('id')->toArray();    
            // dd($courseIds);

            $builder = $builder->whereIn('id', $courseIds);
                // ->joinSub($sub, 'c', function ($join) {
                //     $join->on('courses.id', '=', 'c.course_id');
                //     // $join->on('courses.id', '=', 'c.id');
                // });
                
            // $builder = $builder
            //     ->join(
            //         'course_batch_author', 
            //         'course_batch_author.course_id', 
            //         'courses.id'
            //     )
            //     ->select(
            //         \DB::raw('max(course_batch_author.id) as cba_id'),
            //         'courses.id', 
            //         'courses.title',
            //         'courses.created_at',
            //         'courses.banner_image',
            //         'course_batch_author.course_id'
            //     );

            // if (!\is_null($notAssigned)) 
            // {
            //     $action = '=';
            //     if ($notAssigned === 1) {
            //         $action = '!=';
            //         $builder = $builder->groupBy(
            //             'course_batch_author.course_id'
            //         );
            //     } 
            //     if ($notAssigned === 0) {
            //         $builder = $builder->groupBy(
            //             'course_batch_author.author_id',
            //             'course_batch_author.course_id'
            //         )->orderBy('cba_id', $sort);
            //     } 
            //     $builder = $builder->where('course_batch_author.author_id', $action, $authorId);
            // } else {
            //     $builder = $builder->groupBy('course_batch_author.course_id');
            // }
            
            // if (!\is_null($withBatch))
            // {
            //     $builder = $builder
            //         ->select('courses.*')
            //         ->join(
            //             'course_batches', 
            //             'course_batches.id', 
            //             '=', 
            //             'course_batch_instructor.course_batch_id'
            //         );
            // }    
        }

        if (!\is_null($enrolled) && auth()->check())
        {
            $builder = $builder->whereHas('enrollments', function ($query) {
                auth()->check()
                    ? $query->whereUserId(auth()->user()->id)->whereActive(1)
                    : $query;
            })
            ->join('course_enrollments', 'course_enrollments.course_id', 'courses.id')
            ->where('course_enrollments.user_id', auth()->user()->id)
            ->join('course_batches', 'course_batches.id', 'course_enrollments.course_batch_id')
            ->select(
                'courses.*', 
                'course_batches.start_date',
                'course_batches.batch_name',
                'course_batches.mode_of_delivery',
                'course_batches.end_date',
                'course_batches.price',
                'course_batches.course_id',
                'course_batches.class_is_full'
            );
        }

        // if (!\is_null($minimal) && $minimal === 0)
        // {
        //     $builder = $builder->select('courses.*', \DB::raw('max(course_batch_instructor.id) as cbi_id'));
        // }
        


        // if (!\is_null($notAssigned) && auth()->check()) {
        //     // $builder = $builder->whereHas('instructors', function(Builder $query) use ($authorId) {
        //     //     $query->where('instructor_id', '!=', $authorId);
        //     // });
        //     $action = $notAssigned === 0 ? '=' : '!=';
        //     $builder = $builder->join('course_batch_author as cb', 'cb.course_id', '=', 'courses.id')
        //         ->where('cb.author_id', $action, auth()->user()->id)
        //         ->select('title', 'courses.created_at', 'courses.id')
        //         ->groupBy('title', 'courses.created_at', 'courses.id');
        // }

        $courses = $builder
            ->orderBy('courses.created_at', $sort)
            ->paginate($pageSize, '*', 'page', $page);


        return response()->json(new CourseCollection($courses));
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
            return Course::with('categories')
                ->whereHas('categories', function ($query) {
                    return $query->where(function ($query) {
                        return $query
                            ->orWhere('name', 'associate')
                            ->orWhere('name', 'expert')
                            ->orWhere('name', 'professional');
                    });
                })
                ->get()
                ->groupBy(function ($course) {
                    return $course->categories()->first()->name;
                })
                ->map(function($courseGroup) {
                    return $courseGroup 
                        ->map(function ($course) {
                            return [
                                'title' => $course->title,
                                'slug' => $course->slug,
                            ];
                        })
                        ->take(5);
                });
        });

        return response()->json([
            'message' => 'Successfully fetched course groups',
            'data' => $courses->toArray()
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
        // \Debugbar::enable();
        $courseSlugSegments = explode('_', $course);
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
                // 'instructors' => function($query) {
                //     return $query->where(function($query) {
                //         return $query->groupBy('author_id');
                //     })->with('userable')->where('users.userable_type', '!=', null); },
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

        // dd($course);    

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
    }
}