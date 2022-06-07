<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TeacherModel;
use App\Models\ClassModel;
use App\Models\StudentModel;

class Student extends BaseController
{
    public function __construct()
    {
        if(session()->user['type'] != 'student'){
            dd('galima tik studentui');
        }
    }

    public function index()
    {
        $student = (new StudentModel())->where('user_id', session()->user['id'])->first();
        $class = (new ClassModel())->where('id', $student['class_id'])->first();
        $teacher = (new TeacherModel())
            ->select('teachers.id, users.email, users.firstname, users.lastname')
            ->join('users', 'users.id = teachers.user_id')
            ->where('class_id', $student['class_id'])->first();

        $data = [
            'student' => $student,
            'class' => $class,
            'teacher' => $teacher,
        ];

        return view('users/student', $data);
    }
}
