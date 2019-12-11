<?php

namespace App\Http\Controllers;

use App\User;
use Paystack;
use App\Course;

use App\UserGroup;
use App\CourseBatch;
use App\Transaction;
use App\Http\Requests;
use App\CourseEnrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function redirectToGateway () {
        return Paystack::getAuthorizationUrl()->redirectNow();
    }

    public function handleGatewayCallback (Request $request) {
        logger(request()->all());
        // $paymentDetails = Paystack::getPaymentData();
        $userId = $request['data']['metadata']['userId'] ?? null;
        $courseId = $request['data']['metadata']['courseId'] ?? null;
        $trnxId = $request['data']['reference'] ?? null;
        $status = $request['data']['status'] ?? 'failed';
        $courseBatchId = $request['data']['metadata']['courseBatchId'] ?? null;

        $transaction = Transaction::where('transaction_id', $trnxId)->first();

        $course = Course::find($courseId); 
        $courseBatch = CourseBatch::find($courseBatchId);
        // logger($courseId);
        // logger($courseBatchId);
        // logger($courseBatch);
        // logger($course);
        // if (!$course || $courseBatch) return;
        $courseEnrollment = CourseEnrollment::whereCourseId($courseId)
            ->whereCourseBatchId($courseBatchId)
            ->whereUserId($userId)
            ->first();
            
        $transaction->update([
            'status' => $status,
        ]);

        if ($status === 'success')
        {
            $courseEnrollment->update([
                'active' => 1,
                'expires_at' => null
            ]);
            
            // update group
            $courseGroup = UserGroup::where('group_name', 'like', "%{$course->title}%")->first();
            $courseBatchGroup = UserGroup::where('group_name', 'like', "%{$course->title} ({$courseBatch->batch_name})%")->first();
            $user = User::find($userId);

            logger($courseGroup);
        logger($courseBatchGroup);
            $user->userGroups()->attach([$courseGroup->id, $courseBatchGroup->id]);
            // $user->userGroups()->attach($courseBatchGroup->id);
        }

        // if ($course && session()->has('enrollments.'.$course->id))
        // {
        //     session()->forget('enrollments.'.$course->id);
        // }

        // clear seesion
        // uodate enrollment
        // update transaction

        // logger($paymentDetails);
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }
}