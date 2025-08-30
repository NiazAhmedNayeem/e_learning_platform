<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(){
        return view('student.dashboard.index');
    }

    public function profile(){
        $student = auth()->user();
        //dd($student);
        return view('student.profile.index', compact('student'));
    }

    public function edit(){
        $student = auth()->user();
        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request){

        $student = auth()->user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$student->id,
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->gender = $request->gender;
        $student->expertise_category_id = $request->expertise_category_id;
        $student->profession = $request->profession;
        $student->address = $request->address;
        $student->bio = $request->bio;

        if($request->hasFile('image')){ 
            @unlink(public_path("upload/students/".$student->image));
            $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
            request()->image->move(public_path('upload/students/'),$fileName); 
            $student->image = $fileName; 
        }
        //dd($student);
        $student->save();

        return redirect()->route('student.profile')->with('success', 'Your profile is updated.');

    }

    public function myCourses(Request $request){
        $search = $request->input('search');
        $user = auth()->user()->id;

        $courseItems = OrderItem::with('course', 'order')
        ->whereHas('order', function($q) use ($user) {
            $q->where('user_id', $user)->where('status', 'approved');
        })
        ->when($search, function($q) use ($search) {
            $q->whereHas('course', function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(4);

        //dd($courses);
        return view('student.course.index', compact('courseItems', 'search'));

    }

    public function myCourseDetails($slug){
        $course = Course::where('slug', $slug)->first();
        //dd($course);
        return view('student.course.details', compact('course'));
    }

    public function myCourseOrder(Request $request){
        $user = auth()->id();
        $search = $request->get('search');

        $orders = Order::with('orderItems.course')
            ->where('user_id', $user)
            ->when($search, function($q, $search){
                $q->whereHas('orderItems.course', function($q2) use ($search){
                    $q2->where('title', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        //dd($courses);
        return view('student.order.index', compact('orders', 'search'));
    }
}
