<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Newsletter;

class NewsletterController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Newsletter::isSubscribed($request->email))
        {
            if (Newsletter::subscribe($request->email))
            {
                return response()->json([
                    'message' => 'Successfully added to the mailing list'
                ], 200);
            }

            return response()->json([
                'message' => 'Could not add to the mailing list'
            ], 422);

        }

        return response()->json([
            'message' => 'You are already on the mailing list'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (Newsletter::isSubscribed($request->email))
        {
            if (Newsletter::delete($request->email))
            {
                return response()->json([
                    'message' => 'You have been removed from the mailing list'
                ], 200);
            }

            return response()->json([
                'message' => 'Could not remove user from the mailing list'
            ], 422);

        }

        return response()->json([
            'message' => 'You are not on the mailing list'
        ], 422);
    }
}
