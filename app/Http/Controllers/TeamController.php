<?php

namespace App\Http\Controllers;
use App\Team;
use App\Filters\TeamCollectionFilters;
use App\Http\Resources\TeamCollection;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, TeamCollectionFilters $filters)
    {
        return response()->json(
            new TeamCollection(
                Team::filterUsing($filters)
                    ->latest()
                    ->paginate(
                        $request->pageSize ?? 8,
                        '*',
                        'page',
                        $request->page ?? 1
                    )
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'role' => 'required',
            'avatar' => 'required|file'
        ]);

         // store image file
        $path = $request->avatar->store('team');

         // create the post
        Team::create([
            'avatar' => $path,
            'name' => $request->name,
            'role' => $request->role,
            // 'author_id' => $request->user()->id,
        ]);

        return [
            'message' => 'Successfully created'
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Certification  $certification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $this->validate($request, [
            'name' => 'required',
            'role' => 'required',
            'avatar' => 'required'
        ]);

        if ($request->hasFile('avatar')) {
            // store image file
            $path = $request->avatar->store('team');
            Storage::delete($team->avatar);
            $team->avatar = $path;
        }

        $team->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        return [
            'message' => 'Successfully updated!',
            'model' => $team->fresh()
        ];
    }

    /**
     * get form fields
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function formFields(Request $request, Team $team)
    {
        return [
            'matrix' => [
                'name' => [
                    'type' => 'text',
                    'placeholder' => 'Enter Team member name'
                ],
                'role' => [
                    'type' => 'text',
                    'placeholder' => 'Enter role name'
                ],
                'avatar' => [
                    'type' => 'image'
                ]
            ],
            'model' => [
                'avatar' => isset($team->avatar) ? Storage::url($team->avatar) : null
            ] + $team->toArray(),
            'endpoints' => [
                'createUrl' => route('team.store', [], false),
                'updateUrl' => route('team.update', [
                    'team' => $team->id
                ], false)
            ]
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team  $certification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->forceDelete();
        return response()->json([
            'message' => 'Succesfully deleted',
        ]);
    }
}
