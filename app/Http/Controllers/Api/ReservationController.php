<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ReservationResource;
use App\Http\Resources\SchoolYearResource;
use App\Laravue\Model\Department;
use App\Laravue\Models\Reservation;
use App\Laravue\Models\SchoolYear;
use App\Laravue\Models\SchoolYearFee;
use App\Laravue\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('title') && $request->input('title') != '') {
            $data = Reservation::search($request->title)
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
            $data = Reservation::id($request->id)
                ->with('department')
                ->with('school_year')
                ->with('grade')
                ->with('student')
                ->with('section')
                ->get();

        }
        // check for duplicate
        else if (($request->has('student_id') && $request->input('student_id') != '')
            &&($request->has('school_year') && $request->input('school_year') != '')){
            $data = Reservation::duplicate($request->student_id,$request->school_year)
                ->with('department')
                ->with('school_year')
                ->with('grade')
                ->with('student')
                ->with('section')
                ->get();

        }
        // check for existing reservation
        else if ($request->has('reservation_code') && $request->input('reservation_code') != ''){
            $data = Reservation::reservation($request->reservation_code)
                ->with('department')
                ->with('school_year')
                ->with('grade')
                ->with('student')
                ->with('section')
                ->get();
            if(isset($data[0])){
                $student_id = $data[0]->student->id;
                $student = Student::where('id','=',$student_id)
                    ->with('father')
                    ->with('mother')
                    ->with('guardian')
                    ->get();
                if(isset($student[0]->father)){
                    $data[0]->father = $student[0]->father;
                }
                if(isset($student[0]->mother)){
                    $data[0]->mother = $student[0]->mother;
                }
                if(isset($student[0]->guardian)){
                    $data[0]->guardian = $student[0]->guardian;
                }
            }

        } else {
            $data = Reservation::with('department')
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
        return ReservationResource::collection(['data' => $data]);
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
//         return dd($request->all());
        // validate fields
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => ['required'],
                'last_name' => ['required'],
                'birthdate' => ['required'],
                'mobile' => ['required'],
                'department' => ['required'],
                'grade' => ['required'],
                'section' => ['required'],
                'student_type' => ['required'],
                'remarks' => ['required'],
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            if($params['student_type'] === 'Old Student') {
                $params['student_type'] = 'OLD';
                $params['fee'] = '1000';
            }else{
                $params['student_type'] = 'NEW';
                $params['fee'] = '2000';
            }
            // create school year
            $reservation = Reservation::create([
                'type' => $params['student_type'],
                'school_year_id' => $params['student_id'],
                'student_id' => $params['student_id'],
                'first_name' => $params['first_name'],
                'middle_name' => $params['middle_name'],
                'last_name' => $params['last_name'],
                'mobile' => $params['mobile'],
                'birthdate' => $params['birthdate'],
                'department_id' => $params['department'],
                'grade_id' => $params['grade'],
                'section_id' => $params['section'],
                'student_type' => $params['student_type'],
                'remarks' => $params['remarks'],
                'status' => 'RESERVED',
                'reservation_fee' => $params['fee'],
                'date_reserve' => date('Y-m-d'),
            ]);
            $reservation->reservation_code = 'R-'.str_pad($reservation->id,6,'0', STR_PAD_LEFT);
            $reservation->save();

            return new SchoolYearResource($reservation);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laravue\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Laravue\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        $params = $request->all();
        if($params['student_type'] === 'Old Student') {
            $params['student_type'] = 'OLD';
        }else{
            $params['student_type'] = 'NEW';
        }
        $reservation->first_name = $params['first_name'];
        $reservation->middle_name = $params['middle_name'];
        $reservation->last_name = $params['last_name'];
        $reservation->mobile = $params['mobile'];
        $reservation->birthdate = $params['birthdate'];
        $reservation->department_id = $params['department'];
        $reservation->grade_id = $params['grade'];
        $reservation->section_id = $params['section'];
        $reservation->remarks = $params['remarks'];
        $reservation->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravue\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        try {
            $reservation->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
