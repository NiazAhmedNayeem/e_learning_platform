<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Notice;
use App\Models\Order;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index(){
        $data['students'] = User::where('role', 'student')->where('status', 1)->count();
        $data['teachers'] = User::where('role', 'teacher')->where('status', 1)->count();
        $data['categories'] = Category::where('status', 1)->count();
        $data['admins'] = User::where('role', 'admin')->where('status', 1)->count();
        $data['courses'] = Course::where('status', 1)->count();
        $data['completeOrder'] = Order::where('status', 'approved')->count();
        $data['pendingOrder'] = Order::where('status', 'pending')->count();
        $data['rejectOrder'] = Order::where('status', 'rejected')->count();
        $data['totalAmount'] = Order::where('status', 'approved')->sum('amount');
        $data['assigned_courses'] = Course::whereNotNull('teacher_id')->where('status', 1)->count();
        $data['settings'] = SiteSetting::pluck('value', 'key')->toArray();

        $data['notices'] = Notice::where('status', 'active')
                                ->where('start_at', '<=', Carbon::now())
                                ->where(function($q){
                                    $q->whereNull('end_at')
                                    ->orWhere('end_at', '>=', Carbon::now());
                                })->count();

        return view('backend.dashboard.index', $data);
    }


    // Ajax filter function
    public function filter(Request $request){
    $filter = $request->filter ?? 'all';
    $from = $request->from;
    $to = $request->to;

    // Function to apply date filter
    // $applyDateFilter = function($query) use ($filter, $from, $to) {
    //     if($filter == 'day'){
    //         $query->whereDate('created_at', today());
    //     } elseif($filter == 'week'){
    //         $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    //     } elseif($filter == 'month'){
    //         $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
    //     } elseif($filter == 'year'){
    //         $query->whereYear('created_at', now()->year);
    //     } elseif($filter == 'custom' && $from && $to){
    //         $query->whereBetween('created_at', [$from, $to]);
    //     }
    // };


    $applyDateFilter = function($query, $column = 'created_at') use ($filter, $from, $to) {
        if ($filter == 'day') {
            $query->whereDate($column, today());
        } elseif ($filter == 'week') {
            $query->whereBetween($column, [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter == 'month') {
            $query->whereMonth($column, now()->month)->whereYear($column, now()->year);
        } elseif ($filter == 'year') {
            $query->whereYear($column, now()->year);
        } elseif ($filter == 'custom' && $from && $to) {
            $query->whereBetween($column, [$from, $to]);
        }
    };


    // Orders by status
    $queryApproved = Order::query();
    $applyDateFilter($queryApproved, 'updated_at');
    $totalAmount = $queryApproved->where('status','approved')->sum('amount');
    $completeOrder = $queryApproved->where('status','approved')->count();

    $queryPending = Order::query();
    $applyDateFilter($queryPending, 'updated_at');
    $pendingOrder = $queryPending->where('status','pending')->count();

    $queryReject = Order::query();
    $applyDateFilter($queryReject, 'updated_at');
    $rejectOrder = $queryReject->where('status','rejected')->count();

    // Other counts
    $queryStudents = User::query();
    $applyDateFilter($queryStudents);
    $students = $queryStudents->where('role','student')->where('status',1)->count();

    $queryTeachers = User::query();
    $applyDateFilter($queryTeachers);
    $teachers = $queryTeachers->where('role','teacher')->where('status',1)->count();

    $queryAdmins = User::query();
    $applyDateFilter($queryAdmins);
    $admins = $queryAdmins->where('role','admin')->where('status',1)->count();

    $queryCategories = Category::query();
    $applyDateFilter($queryCategories);
    $categories = $queryCategories->where('status',1)->count();

    $queryCourses = Course::query();
    $applyDateFilter($queryCourses);
    $courses = $queryCourses->where('status',1)->count();

    $queryAssignedCourses = Course::query();
    $applyDateFilter($queryAssignedCourses);
    $assigned_courses = $queryAssignedCourses->whereNotNull('teacher_id')->where('status',1)->count();

    $queryNotice = Notice::query();
    $applyDateFilter($queryNotice);
    $notices = $queryNotice->where('status', 'active')
                                ->where('start_at', '<=', Carbon::now())
                                ->where(function($q){
                                    $q->whereNull('end_at')
                                    ->orWhere('end_at', '>=', Carbon::now());
                                })->count();


    // $students = User::where('role','student')->where('status',1)->count();
    // $teachers = User::where('role','teacher')->where('status',1)->count();
    // $admins = User::where('role','admin')->where('status',1)->count();
    // $categories = Category::where('status',1)->count();
    // $courses = Course::where('status',1)->count();
    // $assigned_courses = Course::whereNotNull('teacher_id')->where('status',1)->count();

    return response()->json([
        'totalAmount' => $totalAmount,
        'completeOrder' => $completeOrder,
        'pendingOrder' => $pendingOrder,
        'rejectOrder' => $rejectOrder,
        'students' => $students,
        'teachers' => $teachers,
        'admins' => $admins,
        'categories' => $categories,
        'courses' => $courses,
        'assigned_courses' => $assigned_courses,
        'notices' => $notices,
    ]);
}









    ///ajax test
    public function ajaxTest(){

        // $categories = Category::all();
        $skills = Skill::where('user_id', auth()->user()->id)->get();
        return view('ajax.index', compact('skills'));
    }

    public function store(Request $request){
        //dd($request->all());
        $skills = $request->input('skill');
        
        
        foreach($skills as $s){
            if(!empty($s)){
                $skill = new Skill();
                $skill->user_id = auth()->user()->id;
                $skill->skill = $s;
                //dd($skill);
                $skill->save();
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Skill added successfully.',
        ]);
        
    }

    public function update(Request $request){
        //dd($request->all());
        $skills = $request->input('skill');
        
        Skill::where('user_id', auth()->user()->id)->delete();
        
        foreach($skills as $s){
            if(!empty($s)){
                $skill = new Skill();
                $skill->user_id = auth()->user()->id;
                $skill->skill = $s;
                //dd($skill);
                $skill->save();
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Skill added successfully.',
        ]);
        
    }









    ///yyajra data table test for user 
    public function usersYajraDatatable(Request $request){
        
        if($request->ajax()){
            $data = User::select('id','name', 'email', 'phone', 'role', 'created_at');
             return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    // '.route('#',$row->id).'
                    $btn = '<a href="" class="edit btn btn-sm btn-primary me-1">Edit</a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger deleteUser" data-id="'.$row->id.'">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action','role']) 
                ->make(true);
        }
     
        return view('ajax.yajra_users_datatable');
    }


}
