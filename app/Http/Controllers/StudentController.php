<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use json;
class StudentController extends Controller
{
    public function index()
    {
        // return response()
        //     ->view('student.index', json([
        //         'name' => 'Abigail',
        //         'state' => 'CA',
        //     ]), 200);
        // return view('student.index')
        
        // ->json([
        //     'name' => 'Abigail',
        //     'state' => 'CA',
        // ]);
        // return Response::view('hello')->header('Content-Type', $type);
        // return view('student.index',compact(json([
        //     'name' => 'Abigail',
        //     'state' => 'CA',
        // ])));
        // $pizza = [[1,2],[2,3],[3,4]];
        // $myObj->name = "John";
        // $myObj->age = 30;
        // $myObj->city = "New York";
        // return view('student.index',['pizza' => $pizza]);

        $exams = array(
                        array(
                        'id'  =>1,
                        'name'  =>'exam 1',
                        'description'=>'Syllabus: chapter 1,2' 
                        ),
                        array(
                            'id'  =>2,
                            'name' => 'exam 2',
                            'description'=>'Syllabus: chapter 1,2' 
                            )
                    );
        return view('student.index',['exams' => $exams]);            
    }
}
