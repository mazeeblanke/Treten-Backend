<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
        $with_muuid = $request->with_muuid ?? 'false';
        $builder = User::with('details')
            ->whereStatus('active')
            ->where(function ($query) use ($q) {
                return $query->orWhere('first_name', 'like', '%' . $q . '%')->orWhere('last_name', 'like', '%' . $q . '%');
            });

        if ($with_muuid == true) {
            $builder = $builder->with(['msuuid:id,sender_id,message_uuid', 'mruuid:id,receiver_id,message_uuid']);
        }    
            
        $users =  $builder->orderBy('users.created_at', 'desc')->paginate($pageSize, '*', 'page', $page);

        return response()->json(array_merge([
            'message' => 'Successfully fetched users',
        ], $users->toArray()));
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

    // public function downloadcsv (Request $request)
    // {
    //     // dd(User::toCSV($type));
    //     return response()->json(
    //         User::toCSV($request->type)
    //     , 200);
    // }

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

    public function currentUser()
    {
        if (auth()->user()) {
            $user = auth()->user()->load(['userable']);
            // $user['gravatar'] = $user->profile_pic ?? Gravatar::get($user->email);
            return response()->json($user, 200);
        }
        return response()->json(['message' => 'Unauthenticated'], 422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request)
    {
        // dd($request->all());
        // $profile_pic = null;
        // Validate the new password length...
        $payload = [
            // 'first_name' => $request->first_name ?? $request->user()->first_name,
            // 'last_name' => $request->last_name ?? $request->user()->last_name,
            'other_name' => $request->other_name ?? $request->user()->other_name ?? '',
            // 'phone_number' => $request->phone_number ?? $request->user()->phone_number,
            // 'profile_pic' => $profile_pic ?? $request->user()->profile_pic,
            // 'email' => $request->email ?? $request->user()->email,
            'password' => !is_null($request->password) 
                ? \Hash::make($request->password) 
                : $request->user()->password,
        ];

        if ($request->hasFile('profile_pic')) {
            $extension = $request->file('profile_pic')->extension();
            $payload['profile_pic'] = $request->file('profile_pic')->storeAs('avatars', "{$request->user()->id}.{$extension}");
        }

       
        $request->user()->fill(array_merge($request->all(), $payload))->save();

        // dd($request->user()->fresh());
        return response()->json($request->user()->refresh(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
