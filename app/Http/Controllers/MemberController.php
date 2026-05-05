<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use Illuminate\Support\MessageBag;
use App\Models\Member;
use App\Models\Department;
use App\Models\Division;

class MemberController extends Controller
{
    public function formValidate (Request $request)
    {
        $rules = [
            'plan_type_id'      => 'required',
            'item_name'         => 'required',
            'unit_id'           => 'required',
        ];

        if ($request['is_addon'] != '1') {
            $rules['category_id'] = 'required';
            $rules['price_per_unit'] = 'required';
        }

        $messages = [
            'plan_type_id.required'     => 'กรุณาเลือกประเภทแผน',
            'category_id.required'        => 'กรุณาเลือกประเภทสินค้า/บริการ',
            'item_name.required'        => 'กรุณาระบุชื่อสินค้า/บริการ',
            'price_per_unit.required'   => 'กรุณาระบุราคาต่อหน่วย',
            'unit_id.required'          => 'กรุณาเลือกหน่วยนับ',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $messageBag = $validator->getMessageBag();

            // if (!$messageBag->has('start_date')) {
            //     if ($this->isDateExistsValidation(convThDateToDbDate($request['start_date']), 'start_date') > 0) {
            //         $messageBag->add('start_date', 'คุณมีการลาในวันที่ระบุแล้ว');
            //     }
            // }

            return [
                'success' => 0,
                'errors' => $messageBag->toArray(),
            ];
        } else {
            return [
                'success' => 1,
                'errors' => $validator->getMessageBag()->toArray(),
            ];
        }
    }

    public function search(Request $req)
    {
        /** Get params from query string */
        $position   = $req->get('position');
        $level      = $req->get('level');
        $name       = $req->get('name');

        $employees = Employee::with('prefix','changwat','amphur','tambon')
                        ->with('position','level','memberOf','memberOf.division')
                        ->when(!empty($position), function($q) use ($position) {
                            $q->where('position_id', $position);
                        })
                        ->when(!empty($level), function($q) use ($level) {
                            $q->where('level_id', $level);
                        })
                        ->when(!empty($name), function($q) use ($name) {
                            $q->where('firstname', 'like', '%'.$name.'%');
                        })
                        // ->when($status != '', function($q) use ($status) {
                        //     $q->where('status', $status);
                        // })
                        ->paginate(10);

        return $employees;
    }

    public function getAll(Request $req)
    {
        /** Get params from query string */
        $position = $req->get('position');
        $level = $req->get('level');

        $employees = Employee::with('prefix','changwat','amphur','tambon')
                        ->with('position','level','memberOf','memberOf.division')
                        ->when(!empty($position), function($q) use ($position) {
                            $q->where('position_id', $position);
                        })
                        ->when(!empty($level), function($q) use ($level) {
                            $q->where('level_id', $level);
                        })
                        // ->when($status != '', function($q) use ($status) {
                        //     $q->where('status', $status);
                        // })
                        ->get();

        return $employees;
    }

    public function getById($id)
    {
        return Member::with('department','division')->find($id);
    }

    public function getByEmployee($employeeId)
    {
        return Member::with('department','division')->where('employee_id', $employeeId)->get();
    }

    public function getInitialFormData()
    {
        return [
            'prefixes'      => Prefix::all(),
            'positions'     => Position::all(),
            'levels'        => Level::all(),
            'departments'   => Department::with('divisions')->get(),
            'divisions'     => Division::all(),
            'changewats'    => Changwat::all(),
            'amphurs'       => Amphur::all(),
            'tambons'       => Tambon::all(),
        ];
    }

    public function store(Request $req)
    {
        try {
            $member = new Member();
            $member->employee_id    = $req['employee_id'];
            $member->duty_id        = $req['duty_id'];
            $member->department_id  = $req['department_id'];
            $member->division_id    = $req['division_id'];

            if($member->save()) {
                return [
                    'status'    => 1,
                    'message'   => 'Insertion successfully!!',
                    'member'    => Member::with('division')->find($member->id)
                ];
            } else {
                return [
                    'status'    => 0,
                    'message'   => 'Something went wrong!!'
                ];
            }
        } catch (\Exception $ex) {
            return [
                'status'    => 0,
                'message'   => $ex->getMessage()
            ];
        }
    }

    public function update(Request $req, $id)
    {
        try {
            $member = Member::find($id);
            $member->employee_id    = $req['employee_id'];
            $member->duty_id        = $req['duty_id'];
            $member->department_id  = $req['department_id'];
            $member->division_id    = $req['division_id'];

            if($member->save()) {
                return [
                    'status'    => 1,
                    'message'   => 'Updating successfully!!',
                    'member'    => $member
                ];
            } else {
                return [
                    'status'    => 0,
                    'message'   => 'Something went wrong!!'
                ];
            }
        } catch (\Exception $ex) {
            return [
                'status'    => 0,
                'message'   => $ex->getMessage()
            ];
        }
    }

    public function destroy(Request $req, $id)
    {
        try {
            // $member = Member::find($id);

            // if($member->delete()) {
            //     return [
            //         'status'    => 1,
            //         'message'   => 'Deleting successfully!!',
            //         'member'    => $member
            //     ];
            // } else {
            //     return [
            //         'status'    => 0,
            //         'message'   => 'Something went wrong!!'
            //     ];
            // }
        } catch (\Exception $ex) {
            return [
                'status'    => 0,
                'message'   => $ex->getMessage()
            ];
        }
    }
}
