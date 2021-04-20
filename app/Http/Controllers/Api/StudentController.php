<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StudentResource;
use App\Laravue\Models\Student;
use App\Laravue\Models\StudentParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends BaseController
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
            $data = Student::search($request->title);

            if ($request->input('type') == 'All') {
                $data = $data->withTrashed();
            } else if ($request->input('type') == 'Trashed') {
                $data = $data->onlyTrashed();
            }

            $data = $data->paginate($request->limit);
        } else if ($request->has('type') && $request->input('type') != '') {
            $data = Student::search($request->title);

            if ($request->input('type') == 'All') {
                $data = $data->withTrashed();
            } else if ($request->input('type') == 'Trashed') {
                $data = $data->onlyTrashed();
            }

            $data = $data->paginate($request->limit);
        } else if ($request->has('id') && $request->input('id') != '') {
            $data = Student::where('id', '=', $request->id)
                ->with('father')
                ->with('mother')
                ->with('guardian')
                ->with('emergency')
                ->get();
        } else if (($request->has('student_no') && $request->input('student_no') != '') &&
            ($request->has('student_id') && $request->input('student_id') != '')) {
            $data = Student::studentNumber($request->student_no, $request->student_id)->get();
        } else if (($request->has('student_no') && $request->input('student_no') != '')) {
            $data = Student::studentNumber($request->student_no, $request->student_id)->get();
        } else {
            $data = Student::paginate($request->limit);
        }

        return StudentResource::collection(['data' => $data]);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return dd($request);
        // validate fields
        $validator = Validator::make(
            $request->all(),
            [
                'lrn' => ['unique:students'],
                'first_name' => ['required'],
                'last_name' => ['required'],
                'gender' => ['required'],
                'birthdate' => ['required'],
                'handedness' => ['required'],
                'mobile' => ['required'],
                'address' => ['required'],
                'emergency' => ['required'],
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $family = $params['family'];
            $father = null;
            $mother = null;
            $guardian = null;
            // create parents
            if (in_array('Father', $family)) {
                $father = StudentParent::create([
                    'first_name' => $params['father_first_name'],
                    'middle_name' => $params['father_middle_name'],
                    'last_name' => $params['father_last_name'],
                    'mobile' => $params['father_mobile'],
                    'email' => $params['father_email'],
                    'address' => $params['father_address'],
                    'occupation' => $params['father_occupation'],
                    'type' => 'Father',
                ]);
                $father_id = $father->id;
            } else {
                $father_id = null;
            }
            if (in_array('Mother', $family)) {
                $mother = StudentParent::create([
                    'first_name' => $params['mother_first_name'],
                    'middle_name' => $params['mother_middle_name'],
                    'last_name' => $params['mother_last_name'],
                    'mobile' => $params['mother_mobile'],
                    'email' => $params['mother_email'],
                    'address' => $params['mother_address'],
                    'occupation' => $params['mother_occupation'],
                    'type' => 'Mother',
                ]);
                $mother_id = $mother->id;
            } else {
                $mother_id = null;
            }
            if (in_array('Guardian', $family)) {
                $guardian = StudentParent::create([
                    'first_name' => $params['guardian_first_name'],
                    'middle_name' => $params['guardian_middle_name'],
                    'last_name' => $params['guardian_last_name'],
                    'mobile' => $params['guardian_mobile'],
                    'email' => $params['guardian_email'],
                    'address' => $params['guardian_address'],
                    'occupation' => $params['guardian_occupation'],
                    'type' => $params['relationship'],
                ]);
                $guardian_id = $guardian->id;
            } else {
                $guardian_id = null;
            }
            // select emergency contact
            if ($params['emergency'] === 'Father') {
                $emergency = $father->id;
            } else if ($params['emergency'] === 'Mother') {
                $emergency = $mother->id;
            } else if ($params['emergency'] === 'Guardian') {
                $emergency = $guardian->id;
            } else {
                $emergency = null;
            }
            // create student
            $student = Student::create([
                'lrn' => $params['lrn'],
                'student_no' => $params['student_no'],
                'first_name' => $params['first_name'],
                'middle_name' => $params['middle_name'],
                'last_name' => $params['last_name'],
                'suffix' => $params['suffix'],
                'nickname' => $params['nickname'],
                'handedness' => $params['handedness'],
                'student_type' => $params['student_type'],
                'religion' => $params['religion'],
                'address' => $params['address'],
                'mobile' => $params['mobile'],
                'email' => $params['email'],
                'gender' => $params['gender'],
                'birthdate' => $params['birthdate'],
                'emergency_contact' => $emergency,
                'father_id' => $father_id,
                'mother_id' => $mother_id,
                'guardian_id' => $guardian_id,
                'avatar' => 'uploads\default.png',
            ]);

            return new StudentResource($student);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Laravue\Models\Student $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Laravue\Models\Student $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Laravue\Models\Student $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $params = $request->all();
        // validate fields
        $validator = Validator::make(
            $request->all(),
            [
                'lrn' => 'unique:students,lrn,' . $params['id'],
                'first_name' => ['required'],
                'last_name' => ['required'],
                'gender' => ['required'],
                'birthdate' => ['required'],
                'handedness' => ['required'],
                'mobile' => ['required'],
                'address' => ['required'],
                'emergency' => ['required'],
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $family = $params['family'];
            $father = null;
            $mother = null;
            $guardian = null;
            // create parents
            if (in_array('Father', $family)) {
                $father = StudentParent::where('id', '=', $student->father_id)
                    ->update([
                        'first_name' => $params['father_first_name'],
                        'middle_name' => $params['father_middle_name'],
                        'last_name' => $params['father_last_name'],
                        'mobile' => $params['father_mobile'],
                        'email' => $params['father_email'],
                        'address' => $params['father_address'],
                        'occupation' => $params['father_occupation'],
                        'type' => 'Father',
                    ]);
                $father_id = $student->father_id;
            } else {
                $father_id = null;
            }
            if (in_array('Mother', $family)) {
                $mother = StudentParent::where('id', '=', $student->mother_id)
                    ->update([
                        'first_name' => $params['mother_first_name'],
                        'middle_name' => $params['mother_middle_name'],
                        'last_name' => $params['mother_last_name'],
                        'mobile' => $params['mother_mobile'],
                        'email' => $params['mother_email'],
                        'address' => $params['mother_address'],
                        'occupation' => $params['mother_occupation'],
                        'type' => 'Mother',
                    ]);
                $mother_id = $student->mother_id;
            } else {
                $mother_id = null;
            }
            if (in_array('Guardian', $family)) {
                $guardian = StudentParent::where('id', '=', $student->guardian_id)
                    ->update([
                        'first_name' => $params['guardian_first_name'],
                        'middle_name' => $params['guardian_middle_name'],
                        'last_name' => $params['guardian_last_name'],
                        'mobile' => $params['guardian_mobile'],
                        'email' => $params['guardian_email'],
                        'address' => $params['guardian_address'],
                        'occupation' => $params['guardian_occupation'],
                        'type' => $params['relationship'],
                    ]);
                $guardian_id = $student->guardian_id;
            } else {
                $guardian_id = null;
            }
            // select emergency contact
            if ($params['emergency'] === 'Father') {
                $emergency = $father_id;
            } else if ($params['emergency'] === 'Mother') {
                $emergency = $mother_id;
            } else if ($params['emergency'] === 'Guardian') {
                $emergency = $guardian_id;
            } else {
                $emergency = null;
            }
            // create student
            Student::where('id', '=', $request->id)
                ->update([
                    'lrn' => $params['lrn'],
                    'student_no' => $params['student_no'],
                    'first_name' => $params['first_name'],
                    'middle_name' => $params['middle_name'],
                    'last_name' => $params['last_name'],
                    'suffix' => $params['suffix'],
                    'nickname' => $params['nickname'],
                    'handedness' => $params['handedness'],
                    'student_type' => $params['student_type'],
                    'religion' => $params['religion'],
                    'address' => $params['address'],
                    'mobile' => $params['mobile'],
                    'email' => $params['email'],
                    'gender' => $params['gender'],
                    'birthdate' => $params['birthdate'],
                    'emergency_contact' => $emergency,
                    'father_id' => $father_id,
                    'mother_id' => $mother_id,
                    'guardian_id' => $guardian_id,
                    'avatar' => 'uploads\default.png',
                ]);

            return new StudentResource($student);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Laravue\Models\Student $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        try {
            $student->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
