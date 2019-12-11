<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseBatch;
use App\Transaction;
use App\CourseEnrollment;
use Illuminate\Http\Request;
use App\Http\Resources\TransactionResource;
use App\Http\Requests\CreateCourseEnrollmentRequest;
use App\Http\Resources\Course as AppCourse;

class CourseEnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(CreateCourseEnrollmentRequest $request)
    {
        // verify id student
        if (auth()->check() && !auth()->user()->isAStudent())
        {
            return response()->json([
                'message' => 'Only students can enroll for courses at the moment'
            ], 422);
        }

        // then find all batches of the course matching the description, mode of delivery and start date,  
        $courseBatch = CourseBatch::find($request->courseBatchId);
        $course = Course::find($request->courseId);
        if (!$courseBatch || !$course)
        {
            return response()->json([
                'message' => 'Date not avaliable'
            ], 422);
        }

         // if found already existing active enrollment, then return a response that user is already enrolled
        // if has active enrollment bail out
        if (auth()->check())
        {
            $previousActiveEnrollment = CourseEnrollment::whereUserId(auth()->user()->id)
                ->whereCourseId($request->courseId)
                ->whereActive(1)
                ->first();

            if ($previousActiveEnrollment)
            {
                $course = $course->load([
                    'transactions' => function ($query) {
                        return $query->whereUserId(auth()->user()->id)->first();
                    },
                    'enrollments' => function ($query) {
                        return $query->whereUserId(auth()->user()->id)->first();
                    }
                ]);
                $course->transaction = $course->transactions[0];
                $course->enrollment = $course->enrollments[0];
                return response()->json([
                    'message' => 'You have already enrolled for this course',
                    'code' => 441,
                    'course' => new AppCourse($course)
                ], 422);
            }
        }

        

        // pick the first one thatis not full(count number of enrollment regardless of the status),
        if ($courseBatch->isFull())
        {
            return response()->json([
                'message' => 'Sorry the class for the selected batch/date is filled up!'
            ], 422);
        }

        // then create an entry in the enrollmnet table with an expiry datetime (if auth)
        //  set at 10 minutes form the current time and status as pending
        if (auth()->check())
        {
            // check if the student has previously enrolled for another mode or date that is pending,
            // if so delete it
            $previousEnrollment = CourseEnrollment::whereUserId(auth()->user()->id)
                ->whereCourseId($request->courseId)
                ->whereActive(0)
                ->first();
            if ($previousEnrollment) $previousEnrollment->delete(); 

            $enrollment = CourseEnrollment::create([
                'active' => 0,
                'user_id' => auth()->user()->id,
                'course_id' => $request->courseId,
                'expires_at' => \Carbon\Carbon::now()->addMinutes(10),
                'course_batch_id' => $request->courseBatchId,
            ]);

            // if all are full return response that ther's no availbale bath matchung this critera
            // in later iterations we will add a cron job to wip out expired entries every minutes

            // create a new transaction and reference with deatils in the databse
            $transaction = Transaction::create([
                'invoice_id' => \str_random(),
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'description' => "{$course->title} {$courseBatch->batch_name}",
                'amount' => $courseBatch->price,
                'transaction_id' => \Paystack::genTranxRef(),
                'user_id' => auth()->user()->id,
                'course_id' => $course->id,
                'course_batch_id' => $courseBatch->id,
                'status' => 'pending'
            ]);

            // then add the details to session including the ref and clear previous session matching this course 
            $transaction->price = $courseBatch->price;
            session()->put([
                'enrollments.'.$course->id => $transaction
            ]);
        }
       

        if (!auth()->check())
        {
            session()->put([
                'enrollments.'.$course->id => [
                    'course_id' => $course->id,
                    'plan' => $request->plan,
                    'availableDate' => $request->availableDate,
                    'courseBatchId' => $courseBatch->id,
                    'transactionId' => null,
                    'invoiceId' => null,
                    'amount' => $courseBatch->price,
                    'price' => $courseBatch->price,
                ]
            ]);

            return response()->json([
                'message' => 'Successfully initialized enrollment',
                'data' => [
                    'data' => [
                        'courseId' => $course->id,
                        'plan' => $request->plan,
                        'availableDate' => $request->availableDate,
                        'courseBatchId' => $courseBatch->id,
                        'transactionId' => null,
                        'invoiceId' => null,
                        'amount' => $courseBatch->price,
                        'price' => $courseBatch->price,
                    ]
                ]
            ]);
        }

        

        

        // return deatils
        return response()->json([
            'message' => 'Successfully initialized enrollment',
            'data' => new TransactionResource($transaction)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CourseEnrollment  $courseEnrollment
     * @return \Illuminate\Http\Response
     */
    public function show(CourseEnrollment $courseEnrollment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CourseEnrollment  $courseEnrollment
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseEnrollment $courseEnrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CourseEnrollment  $courseEnrollment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseEnrollment $courseEnrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CourseEnrollment  $courseEnrollment
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseEnrollment $courseEnrollment)
    {
        //
    }
}
