<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EnrollmentResource;
use App\Laravue\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('title') && $request->input('title') != '') {
            $data = Enrollment::search($request->title)
                ->with('department')
                ->with('school_year')
                ->with('grade')
                ->with('section')
                ->with('student');

            if ($request->input('type') == 'All') {
                $data = $data->withTrashed();
            } else if ($request->input('type') == 'Active') {
                $data = $data->active(1);
            } else if ($request->input('type') == 'Trashed') {
                $data = $data->onlyTrashed();
            }

            $data = $data->paginate($request->limit);
        } else if ($request->has('id') && $request->input('id') != '') {
            $data = Enrollment::id($request->id)
                ->with('department')
                ->with('school_year')
                ->with('grade')
                ->with('student')
                ->with('section')
                ->get();

        } else {
            $data = Enrollment::with('department')
                ->with('school_year')
                ->with('grade')
                ->with('section')
                ->with('student');

            if ($request->input('type') == 'All') {
                $data = $data->withTrashed();
            } else if ($request->input('type') == 'Active') {
                $data = $data->active(1);
            } else if ($request->input('type') == 'Trashed') {
                $data = $data->onlyTrashed();
            }

            $data = $data->paginate($request->limit);
        }
        return EnrollmentResource::collection(['data' => $data]);
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
     * @param  \App\Laravue\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function show(Enrollment $enrollment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Laravue\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function edit(Enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravue\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Enrollment $enrollment)
    {
        //
    }
}
