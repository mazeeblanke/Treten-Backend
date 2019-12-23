<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Resources\SettingCollection;
use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new SettingCollection(Setting::all()->map(function($setting) {
            if (
                $setting->setting_name === 'popularCourses' &&
                $setting->setting_value
            ) {
                $ids = unserialize($setting->setting_value);
                $setting->setting_value =  [
                    "ids" => json_decode($ids, false),
                    "courses" => Course::whereIn('id', array_values(json_decode($ids, true)))->get(['id', 'title']),
                ];
            }
            return $setting;
        })->keyBy('setting_name'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        if ($request->has('popularCourses')) {
           $setting = Setting::whereSettingName('popularCourses')->first();
        //    dd();
           $encoded = json_encode($request->popularCourses);
           $setting->update(['setting_value' => serialize($encoded)]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
