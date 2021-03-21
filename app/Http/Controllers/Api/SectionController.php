<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SectionResource;
use App\Laravue\Models\Section;
use Illuminate\Http\Request;

class SectionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Support\Facades\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('title') && $request->input('title') != '') {
            $data = null;
        } else if ($request->has('grade_id') && $request->input('grade_id') != '') {
            $data = Section::where('grade_id','=',$request->grade_id)
                ->get();
        } else {
            if ($request->has('limit') && $request->input('limit') != ''){
                $data = Section::paginate($request->limit);
            } else {
                $data = Section::all();
            }
        }
        return SectionResource::collection(['data' => $data]);
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
     * @param  \App\Laravue\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Laravue\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravue\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        //
    }
}
