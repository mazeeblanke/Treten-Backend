<?php

namespace App\Http\Controllers;

use App\CourseReview;
use App\User;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DownloadController extends Controller
{
    public function downloadcsv (Request $request)
    {
        $data = [];

        if ($request->type === 'instructors' || $request->type === 'students' || $request->type === 'admins') {
            $data = User::toCSV($request->type);
        }

        if ($request->type === 'transactions') {
            $data = Transaction::toCSV();
        }

        if ($request->type === 'reviews') {
            $data = CourseReview::toCSV();
        }

        return response()->json($data, 200);
    }
}
