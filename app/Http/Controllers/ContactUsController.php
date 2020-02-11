<?php

namespace App\Http\Controllers;

use App\ContactUs;
use App\Http\Requests\CreateContactUsRequest;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateContactUsRequest $request)
    {
        ContactUs::initialize($request->all())->fire();

        // add the email to a job
        return response()->json([
            'message' => 'successfully contacted treten!'
        ], 200);
    }
}
