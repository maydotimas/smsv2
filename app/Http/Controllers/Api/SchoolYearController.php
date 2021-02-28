<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SchoolYearResource;
use App\Laravue\Model\Department;
use App\Laravue\Models\SchoolYear;
use App\Laravue\Models\SchoolYearFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolYearController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('title') && $request->input('title') != '') {
            $data = SchoolYear::search($request->title);

            if ($request->input('type') == 'Active') {
                $data = $data->active(1);
            } else if ($request->input('type') == 'Inactive') {
                $data = $data->active(0);
            }

            $data = $data->paginate($request->limit);
        }
        else if ($request->has('type') && $request->input('type') != '') {
            if ($request->input('type') == 'Active') {
                $data = SchoolYear::active(1)->paginate($request->limit);
            } else if ($request->input('type') == 'Inactive') {
                $data = SchoolYear::active(0)->paginate($request->limit);
            } else {
                $data = SchoolYear::paginate($request->limit);
            }

        }
        else if ($request->has('year') && $request->input('year') != '') {
            if ($request->input('is_edit') === 'true'){
                $data = SchoolYear::where('year','=',$request->year)
                    ->where('id','!=',$request->id)
                    ->get();
            }else{
                $data = SchoolYear::where('year','=',$request->year)->get();
            }
        }
        else if ($request->has('name') && $request->input('name') != '') {
            if ($request->input('is_edit') === 'true'){
                $data = SchoolYear::where('name','=',$request->year)
                    ->where('id','!=',$request->id)
                    ->get();
            }else {
                $data = SchoolYear::where('name', '=', $request->name)->get();
            }
        }
        else if ($request->has('id') && $request->input('id') != '') {
            $data = SchoolYear::where('id', '=', $request->id)
                ->with('department_fees')
                ->get();
        }
        else if ($request->has('department') && $request->input('department') != '') {
            $data = SchoolYearFee::where('department_id', '=', $request->department)
                ->where('school_year_id','=',$request->school_year_id)
                ->get();
        }
        else {
            $data = SchoolYear::paginate($request->limit);
        }
        return SchoolYearResource::collection(['data' => $data]);
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
//        return dd($request->all());
        // validate fields
        $validator = Validator::make(
            $request->all(),
            [
//                'year' => ['required', 'unique:school_years,year'],
//                'name' => ['required','unique:school_years,name'],
                'description' => ['required'],
                'start' => ['required'],
                'end' => ['required'],
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            // create school year
            $schoolyear = SchoolYear::create([
                'name' => $params['name'],
                'year' => $params['year'],
                'start' => $params['start'],
                'end' => $params['end'],
                'description' => $params['description'],
                'status' => 0,
            ]);
            foreach($params['fee'] as $fee){
                $department = Department::where('name','=',$fee)->get();
                $department_id = $department[0]['id'];
                // create school year department fees
                $department_fees = $params['department_fees'];
                foreach ($department_fees as $department_fee){
                    if($department_fee['department_id'] === $department_id){
                        SchoolYearFee::create([
                            'school_year_id' => $schoolyear->id,
                            'department_id' => $department_fee['annual']['department_id'],
                            'type' => 'ANNUAL',
                            'total_tuition_fee' => $department_fee['annual']['total_tuition_fee'],
                            'total_misc_fee' => $department_fee['annual']['total_misc_fee'],
                            'enrollment_tuition_fee' => $department_fee['annual']['enrollment_tuition_fee'],
                            'enrollment_misc_fee' => $department_fee['annual']['enrollment_misc_fee'],
                            'monthly_tuition_fee' => $department_fee['annual']['monthly_tuition_fee'],
                            'monthly_misc_fee' => $department_fee['annual']['monthly_misc_fee'],
                        ]);
                        SchoolYearFee::create([
                            'school_year_id' => $schoolyear->id,
                            'department_id' => $department_fee['semestral']['department_id'],
                            'type' => 'SEMESTRAL',
                            'total_tuition_fee' => $department_fee['semestral']['total_tuition_fee'],
                            'total_misc_fee' => $department_fee['semestral']['total_misc_fee'],
                            'enrollment_tuition_fee' => $department_fee['semestral']['enrollment_tuition_fee'],
                            'enrollment_misc_fee' => $department_fee['semestral']['enrollment_misc_fee'],
                            'monthly_tuition_fee' => $department_fee['semestral']['monthly_tuition_fee'],
                            'monthly_misc_fee' => $department_fee['semestral']['monthly_misc_fee'],
                        ]);
                        SchoolYearFee::create([
                            'school_year_id' => $schoolyear->id,
                            'department_id' => $department_fee['quarterly']['department_id'],
                            'type' => 'QUARTERLY',
                            'total_tuition_fee' => $department_fee['quarterly']['total_tuition_fee'],
                            'total_misc_fee' => $department_fee['quarterly']['total_misc_fee'],
                            'enrollment_tuition_fee' => $department_fee['quarterly']['enrollment_tuition_fee'],
                            'enrollment_misc_fee' => $department_fee['quarterly']['enrollment_misc_fee'],
                            'monthly_tuition_fee' => $department_fee['quarterly']['monthly_tuition_fee'],
                            'monthly_misc_fee' => $department_fee['quarterly']['monthly_misc_fee'],
                        ]);
                        SchoolYearFee::create([
                            'school_year_id' => $schoolyear->id,
                            'department_id' => $department_fee['monthly']['department_id'],
                            'type' => 'MONTHLY',
                            'total_tuition_fee' => $department_fee['monthly']['total_tuition_fee'],
                            'total_misc_fee' => $department_fee['monthly']['total_misc_fee'],
                            'enrollment_tuition_fee' => $department_fee['monthly']['enrollment_tuition_fee'],
                            'enrollment_misc_fee' => $department_fee['monthly']['enrollment_misc_fee'],
                            'monthly_tuition_fee' => $department_fee['monthly']['monthly_tuition_fee'],
                            'monthly_misc_fee' => $department_fee['monthly']['monthly_misc_fee'],
                        ]);
                    }
                }
            }
            return new SchoolYearResource($schoolyear);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laravue\Models\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolYear $schoolYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Laravue\Models\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function edit(SchoolYear $schoolYear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolYear $schoolYear)
    {
//        return dd($request->all());
        // validate fields
        $validator = Validator::make(
            $request->all(),
            [
                'year' => ['required', 'unique:school_years,year,'.$request->id],
                'name' => ['required','unique:school_years,name,'.$request->id],
                'description' => ['required'],
                'start' => ['required'],
                'end' => ['required'],
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            // update school year
            SchoolYear::where('id','=',$request->id)
                ->update([
                'name' => $params['name'],
                'year' => $params['year'],
                'start' => $params['start'],
                'end' => $params['end'],
                'description' => $params['description'],
                'status' => 0,
            ]);

            // clear all school year department fees
            SchoolYearFee::where('school_year_id','=',$request->id)->delete();

            foreach($params['fee'] as $fee){
                $department = Department::where('name','=',$fee)->get();
                $department_id = $department[0]['id'];
                // create school year department fees
                $department_fees = $params['department_fees'];
                foreach ($department_fees as $department_fee){
                    if($department_fee['department_id'] === $department_id){
                        SchoolYearFee::create([
                            'school_year_id' => $request->id,
                            'department_id' => $department_fee['annual']['department_id'],
                            'type' => 'ANNUAL',
                            'total_tuition_fee' => $department_fee['annual']['total_tuition_fee'],
                            'total_misc_fee' => $department_fee['annual']['total_misc_fee'],
                            'enrollment_tuition_fee' => $department_fee['annual']['enrollment_tuition_fee'],
                            'enrollment_misc_fee' => $department_fee['annual']['enrollment_misc_fee'],
                            'monthly_tuition_fee' => $department_fee['annual']['monthly_tuition_fee'],
                            'monthly_misc_fee' => $department_fee['annual']['monthly_misc_fee'],
                        ]);
                        SchoolYearFee::create([
                            'school_year_id' => $request->id,
                            'department_id' => $department_fee['semestral']['department_id'],
                            'type' => 'SEMESTRAL',
                            'total_tuition_fee' => $department_fee['semestral']['total_tuition_fee'],
                            'total_misc_fee' => $department_fee['semestral']['total_misc_fee'],
                            'enrollment_tuition_fee' => $department_fee['semestral']['enrollment_tuition_fee'],
                            'enrollment_misc_fee' => $department_fee['semestral']['enrollment_misc_fee'],
                            'monthly_tuition_fee' => $department_fee['semestral']['monthly_tuition_fee'],
                            'monthly_misc_fee' => $department_fee['semestral']['monthly_misc_fee'],
                        ]);
                        SchoolYearFee::create([
                            'school_year_id' => $request->id,
                            'department_id' => $department_fee['quarterly']['department_id'],
                            'type' => 'QUARTERLY',
                            'total_tuition_fee' => $department_fee['quarterly']['total_tuition_fee'],
                            'total_misc_fee' => $department_fee['quarterly']['total_misc_fee'],
                            'enrollment_tuition_fee' => $department_fee['quarterly']['enrollment_tuition_fee'],
                            'enrollment_misc_fee' => $department_fee['quarterly']['enrollment_misc_fee'],
                            'monthly_tuition_fee' => $department_fee['quarterly']['monthly_tuition_fee'],
                            'monthly_misc_fee' => $department_fee['quarterly']['monthly_misc_fee'],
                        ]);
                        SchoolYearFee::create([
                            'school_year_id' => $request->id,
                            'department_id' => $department_fee['monthly']['department_id'],
                            'type' => 'MONTHLY',
                            'total_tuition_fee' => $department_fee['monthly']['total_tuition_fee'],
                            'total_misc_fee' => $department_fee['monthly']['total_misc_fee'],
                            'enrollment_tuition_fee' => $department_fee['monthly']['enrollment_tuition_fee'],
                            'enrollment_misc_fee' => $department_fee['monthly']['enrollment_misc_fee'],
                            'monthly_tuition_fee' => $department_fee['monthly']['monthly_tuition_fee'],
                            'monthly_misc_fee' => $department_fee['monthly']['monthly_misc_fee'],
                        ]);
                    }
                }
            }

            return new SchoolYearResource($schoolYear);
        }
    }

    public function destroy($id)
    {
        try {
            $schoolYear = SchoolYear::where('id','=', $id);
            $schoolYear->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    public function lock(Request $request, $id){
        $sy = SchoolYear::find($id);
        $data = SchoolYear::where('id','=',$id)
            ->update([
                'is_locked' => $request->lock_status,
            ]);
        return new SchoolYearResource($sy);
    }
    public function status(Request $request, $id){
        $sy = SchoolYear::find($id);
        SchoolYear::where('id','>',0)->update([
                'status' => 0,
            ]);
        SchoolYear::where('id','=',$id)
            ->update([
                'status' => $request->active_status,
            ]);
        return new SchoolYearResource($sy);
    }
}
