<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EnrollmentResource;
use App\Laravue\Models\Enrollment;
use App\Laravue\Models\EnrollmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        }  else if (($request->has('school_year_id') && $request->input('school_year_id') != '') &&
            ($request->has('student_id') && $request->input('student_id') != '')) {
            $data = Enrollment::duplicate($request->school_year_id, $request->student_id)
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
//        return dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'student_id' => ['required'],
                'school_year_id' => ['required'],
                'department' => ['required'],
                'grade' => ['required'],
                'section' => ['required'],
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            // create enrollment record
            $enrollment = Enrollment::create([
                'student_id' => $params['student_id'],
                'school_year_id' => $params['school_year_id'],
                'department_id' => $params['department'],
                'grade_id' => $params['grade'],
                'section_id' => $params['section'],
                'reservation_id' => $params['reservation_id'],
                'reservation_fee' => $params['tuition'][0]['reservation_fee'],
                'type' => $params['type'],
                'date_enrolled' => date('Y-m-d'),
                'status' => 'ENROLLED',
                'discount' => $params['discount'],
                'remarks' => $params['remarks'],
                'percent' => $params['percent'],
                'payment_mode' => $params['tuition'][0]['type'],
                'months' => $params['tuition'][0]['months'],
                'discount_amount' => $params['tuition'][0]['discount'],
                'total_amount' => $params['tuition'][0]['total'],
                'total_tf' => $params['tuition'][0]['total_tuition_fee'],
                'total_misc' => $params['tuition'][0]['total_misc_fee'],
                'monthly_tuition_fee' => $params['tuition'][0]['monthly_tuition_fee'],
                'monthly_misc_fee' => $params['tuition'][0]['monthly_misc_fee'],
                'enrollment_tuition_fee' => $params['tuition'][0]['enrollment_tuition_fee'],
                'enrollment_misc_fee' => $params['tuition'][0]['enrollment_misc_fee'],
                'processor_id' => Auth::id(),
            ]);
            // payments schedule
            foreach ($params['payment_schedule'] as $payment_schedule){
                EnrollmentPayment::create([
                   'school_year_id' => $params['school_year_id'],
                   'enrollment_id' => $enrollment->id,
                   'student_id' => $params['student_id'],
                   'payment_no' => $payment_schedule['payment_no'],
                   'payment_tf_amount' => $payment_schedule['tuition_fee'],
                   'payment_misc_amount' => $payment_schedule['misc_fee'],
                   'total_amount' => $payment_schedule['total'],
                   'payment_due' => $payment_schedule['due_date'],
                   'interest' => 0,
                   'accomplished_date' => NULL,
                   'remarks' => $payment_schedule['remarks'],
                   'status' => $payment_schedule['remarks'] == 'PAID' ? 'PAID' : 'UNPAID',
                ]);
            }
        }
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
