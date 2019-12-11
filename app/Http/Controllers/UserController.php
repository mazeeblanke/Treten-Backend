<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
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

        $users = $builder->orderBy('users.created_at', 'desc')->paginate($pageSize, '*', 'page', $page);

        return response()->json((new UserCollection($users))->additional([
            'message' => 'Successfully fetched users',
        ]));

        // return response()->json(array_merge([
        //     'message' => 'Successfully fetched users',
        // ], $users->toArray()));
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
            return response()->json(new UserResource($user), 200);
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
        if ($request->has('oldPassword') && !\Hash::check($request->oldPassword, $request->user()->password)) {
            return response()->json([
                'errors' => [
                   'oldPassword' => ['The specified password does not match the database password']
                ]
            ], 422);
        }
        $payload = [
            'first_name' => $request->firstName ?? $request->user()->first_name,
            'last_name' => $request->lastName ?? $request->user()->last_name,
            'other_name' => $request->otherName,
            'phone_number' => $request->phoneNumber,
            'email' => $request->email ?? $request->user()->email,
            'title' => $request->title,
            'password' => !is_null($request->password)
            ? \Hash::make($request->password)
            : $request->user()->password,
        ];

        if ($request->hasFile('profilePic')) {
            $extension = $request->file('profilePic')->extension();
            $payload['profile_pic'] = $request
                ->file('profilePic')
                ->storeAs('avatars', "{$request->user()->id}.{$extension}");
        }

        $request->user()->fill($payload)->save();

        return response()->json(new UserResource($request->user()->refresh()), 200);
    }


    public function handleActivation (Request $request, User $user)
    {
        $updatedUser = $user->update([
            'status' => $request->deactivate ? 'inactive' : 'active'
        ]);

        if ($updatedUser) {
            return response()->json([
                'message' => 'Successfully updated user',
                'data' => new UserResource(User::whereId($user->id)->with('userable')->first())
            ]);
        }

        return response()->json([
            'message' => 'Unable to update user',
        ], 422);
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