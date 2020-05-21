<?php

namespace App\Http\Controllers;

use App\User;
use App\Instructor;
use App\Invitation;
use App\Mail\InvitationMail;
use Illuminate\Http\Request;
use App\Events\NewUserRegistered;
use Spatie\Permission\Models\Role;
use App\Http\Requests\InviteUserRequest;

class InviteUserController extends Controller
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
    public function store(InviteUserRequest $request)
    {
        // validate role must be one of 'student', 'instructor', 'admin'
        $role = $request->role;
        $invitationsToDelete = [];
        $emailsReinvitedForDifferentRole = [];
        // send just an array of emails
        $newEmailsToInvite = $emailsToAddToAddToDb = $request->emails;

        $alreadyInvitedEmails = \DB::table('user_invitations')
            ->whereIn('email', $request->emails)
            ->get(['email', 'role'])
            ->toArray();

        if (count($alreadyInvitedEmails) > 0) {

            $emailsReinvitedForDifferentRole = \array_filter($alreadyInvitedEmails, function ($email) use ($role) {
                return $email->role !== $role;
            });

            if (count($emailsReinvitedForDifferentRole) > 0) {
                $invitationsToDelete = collect($emailsReinvitedForDifferentRole)->pluck('email')->values()->toArray();
                \DB::table('user_invitations')->whereIn('email', $invitationsToDelete)->delete();
            }

            $alreadyInvitedEmailsOnlyEmailColumn = array_column($alreadyInvitedEmails, 'email');
            $emailsToAddToAddToDb = \array_filter($newEmailsToInvite, function ($email) use ($alreadyInvitedEmailsOnlyEmailColumn, $invitationsToDelete) {
                return !\in_array($email, $alreadyInvitedEmailsOnlyEmailColumn)
                    || \in_array($email, $invitationsToDelete);
            });
        }

        $invitationData = array_map(function ($email) use ($role) {
            return [
                'email' => $email,
                'role' => $role,
                'token' => str_random(64)
            ];
        }, $emailsToAddToAddToDb);

        \DB::table('user_invitations')->insert($invitationData);

        // send emails to everybody

        $userInvitations = \DB::table('user_invitations')
            ->whereIn('email', $newEmailsToInvite)
            ->get();

        // For each of the emails queue an actual email
        $userInvitations->each(function ($userInvitation) {
            \Mail::to($userInvitation->email)
                ->send(new InvitationMail((array) $userInvitation));
        });

        return response()->json([
            'message' => 'Successfully invited users'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($token)
    {
        //
        // $invitation = Invitation::whereToken($token)->first();

        // if (!$invitation) {
        //     exit('Invalid Token');
        // } 

        return view('invitation');

    }

    public function complete (Request $request) {
        // logger($request->all());
        // validate request
        $invitation = Invitation::whereToken($request->token)->first();

        if (!$invitation) {
            return response()->json([
                'message'  => 'The provided token is invalid'
            ], 422);
        } 
        // dd($invitation);
        if ($user = User::whereEmail($invitation->email)->first()) {
            \Auth::login($user);
            return response()->json([
                'message' => 'Already accepted invitation'
            ], 200);
        }
        $payload = [
            'email' => $invitation->email,
            'password' => $request->password,
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
        ];
        $request->request->add($payload);

        $role = Role::firstOrCreate(
            ['name' => $invitation->role], 
            ['name' => $invitation->role]
        );

        if ($invitation->role === 'admin') {
            $user = User::create($payload);
        } else if ($invitation->role === 'instructor') {
            $user = Instructor::register($payload);
        }
        
        $user->assignRole($role);

        event(new NewUserRegistered($user));

        \Auth::logout();
        \Auth::loginUsingId($user->id);

        // delete token 

        $invitation->delete();

        if ($request->wantsJson())
        {
            return response()->json([
                'message' => 'Successfully accepted invitation'
            ], 200);
        }

        return redirect()->to(config('app.frontend_url').'/d/profile/details');
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
    public function destroy($id)
    {
        //
    }

    // protected function getUserType ($entity)
    // {
    //     return "App\\".Str::ucfirst($entity);
    // }
}
