<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LessonModel;
use App\Models\TeacherModel;
use App\Models\ClassModel;
use App\Models\UserModel;
use App\Models\StudentModel;

class Director extends BaseController
{
    public function __construct()
    {
        if (session()->user['type'] != 'director') {
            dd('galima tik direktoriui');
        }
    }

    public function index()
    {
        $data = [
            'lessons' => (new LessonModel)->findAll(),
            'classes' => (new ClassModel)->findAll(),
            'teachers' => (new TeacherModel)->findAllWithRelations(),
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
        ];

        return view('users/director/home', $data);
    }

    public function teachers()
    {
        $data = [
            'lessons' => (new LessonModel)->findAll(),
            'classes' => (new ClassModel)->findAll(),
            'teachers' => (new TeacherModel)->findAllWithRelations(),
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
        ];

        return view('users/director/teachers', $data);
    }


    public function classes()
    {
        $data = [
            'classes' => (new ClassModel)->findAll(),
        ];

        return view('users/director/classes', $data);
    }

    public function lessons(int $id = null)
    {
        $data = [
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
            'lessons' => (new LessonModel)->findAll()
        ];

        if ($id != null) {
            $data['lesson'] = (new LessonModel)->find($id);
        }

        return view('users/director/lessons', $data);
    }

    public function students($id = null)
    {
        $data = [
            'classes' => (new ClassModel)->findAll(),
            'students' => (new StudentModel)->getWithRelations(),
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
        ];

        if ($id != null) {
            $data['student'] = (new StudentModel)->getWithRelations($id);
        }

        return view('users/director/students', $data);
    }

    public function createTeacher()
    {
        if ($this->validate([
            'password' => 'required|min_length[2]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'firstname' => 'required|min_length[2]|max_length[60]',
            'lastname' => 'required|min_length[2]|max_length[60]',
            'lesson_id' => 'permit_empty|is_not_unique[lessons.id]',
            'class_id' => 'permit_empty|is_not_unique[classes.id]',
        ])) {
            $user_data = [
                'email' => $this->request->getVar('email'),
                'password' => md5($this->request->getVar('password')),
                'firstname' => $this->request->getVar('firstname'),
                'lastname' => $this->request->getVar('lastname'),
                'type' => 'teacher'
            ];
            $user_id = (new UserModel)->insert($user_data);

            $teacher_data = [
                'user_id' => $user_id,
                'lesson_id' => $this->request->getVar('lesson_id') ?? null,
                'class_id' => $this->request->getVar('class_id') ?? null,
            ];
            (new TeacherModel)->insert($teacher_data);

            return redirect()->to(base_url('/director/index'))->with('success', 'Mokytojas sėkimingai sukurtas');
        } else {
            return redirect()->to(base_url('/director/index'))->with('errors', $this->validator->listErrors());
        }
    }

    public function editTeacher(int $id)
    {
        $teacher = (new TeacherModel())->getFullData($id);
        if ($teacher) {
            $data = [
                'lessons' => (new LessonModel)->findAll(),
                'classes' => (new ClassModel)->findAll(),
                'teacher' => $teacher
            ];

            return view('users/director/teacher_edit', $data);
        }

        return redirect()->to(base_url('/director/index'))->with('errors', 'mokytojas nerastas');
    }

    public function updateTeacher(int $id)
    {
        $teacher = (new TeacherModel())->getFullData($id);
        if ($teacher) {
            if ($this->validate([
                'password' => 'permit_empty|min_length[2]',
                'email' => 'required|valid_email|is_unique[users.email,id,' . $teacher['user_id'] . ']',
                'firstname' => 'required|min_length[2]|max_length[60]',
                'lastname' => 'required|min_length[2]|max_length[60]',
                'lesson_id' => 'permit_empty|is_not_unique[lessons.id]',
                'class_id' => 'permit_empty|is_not_unique[classes.id]',
            ])) {
                $userData = [
                    'email' => $this->request->getVar('email'),
                    'firstname' => $this->request->getVar('firstname'),
                    'lastname' => $this->request->getVar('lastname'),
                ];

                $password = $this->request->getVar('password') ?? null;
                if ($password != null) {
                    $userData['password'] = md5($this->request->getVar('password'));
                }

                (new UserModel())->update($teacher['user_id'], $userData);

                (new TeacherModel())->update($id, [
                    'lesson_id' => $this->request->getVar('lesson_id') ?? null,
                    'class_id' => $this->request->getVar('class_id') ?? null,
                ]);

                return redirect()->to(base_url('/director/index'))->with('success', 'Mokytojas sėkimingai atnaujintas');
            }
        }

        return redirect()->to(base_url('/director/index'))->with('errors', 'mokytojas nerastas');
    }

    public function createStudent()
    {
        if ($this->validate([
            'password' => 'required|min_length[2]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'firstname' => 'required|min_length[2]|max_length[60]',
            'lastname' => 'required|min_length[2]|max_length[60]',
            'class_id' => 'permit_empty|is_not_unique[classes.id]',
        ])) {
            $user_data = [
                'email' => $this->request->getVar('email'),
                'password' => md5($this->request->getVar('password')),
                'firstname' => $this->request->getVar('firstname'),
                'lastname' => $this->request->getVar('lastname'),
                'type' => 'student'
            ];
            $user_id = (new UserModel)->insert($user_data);

            $student_data = [
                'user_id' => $user_id,
                'class_id' => $this->request->getVar('class_id') ?? null,
            ];
            (new StudentModel)->insert($student_data);

            return redirect()->to(base_url('/director/students'))->with('success', 'Moksleivis sėkmingai pridėtas');
        } else {
            return redirect()->to(base_url('/director/students'))->with('errors', $this->validator->listErrors());
        }
    }

    public function updateStudent(int $id)
    {
        $student = (new StudentModel())->getWithRelations($id);
        if ($student) {
            if ($this->validate([
                'password' => 'permit_empty|min_length[2]',
                'email' => 'required|valid_email|is_unique[users.email,id,' . $student['user_id'] . ']',
                'firstname' => 'required|min_length[2]|max_length[60]',
                'lastname' => 'required|min_length[2]|max_length[60]',
                'lesson_id' => 'permit_empty|is_not_unique[lessons.id]',
                'class_id' => 'permit_empty|is_not_unique[classes.id]',
            ])) {
                $userData = [
                    'email' => $this->request->getVar('email'),
                    'firstname' => $this->request->getVar('firstname'),
                    'lastname' => $this->request->getVar('lastname'),
                ];

                $password = $this->request->getVar('password') ?? null;
                if ($password != null) {
                    $userData['password'] = md5($this->request->getVar('password'));
                }

                (new UserModel())->update($student['user_id'], $userData);

                (new StudentModel())->update($id, [
                    'class_id' => $this->request->getVar('class_id') ?? null,
                ]);

                return redirect()->to(base_url('/director/students'))->with('success', 'Moksleivis sėkimingai atnaujintas');
            }
        }

        return redirect()->to(base_url('/director/students'))->with('errors', 'Moksleivis nerastas');
    }

    public function deleteStudent($id)
    {
        $student = (new StudentModel())->find($id);
        if ($student) {
            (new UserModel())->delete($student['user_id']);
            (new StudentModel())->delete($student['id']);

            return redirect()->to(base_url('/director/students'))->with('success', 'Moksleivis sėkimingai ištrintas');
        }

        return redirect()->to(base_url('/director/students'))->with('errors', 'Moksleivis nerastas');
    }

    public function createLesson()
    {
        if ($this->validate([
            'title' => 'required|min_length[2]|max_length[30]',
        ])) {
            (new LessonModel())->insert([
                'title' => $this->request->getVar('title'),
            ]);

            return redirect()->to(base_url('/director/lessons'))->with('success', 'Pamoka sėkmingai sukurta');
        } else {
            return redirect()->to(base_url('/director/lessons'))->with('errors', $this->validator->listErrors());
        }
    }

    public function updateLesson(int $id)
    {
        $lesson = (new LessonModel())->find($id);
        if ($lesson) {
            if ($this->validate([
                'title' => 'required|min_length[2]|max_length[30]|is_unique[lessons.title,id,' . $id . ']',
            ])) {
                (new LessonModel())->update($id, [
                    'title' => $this->request->getVar('title'),
                ]);

                return redirect()->to(base_url('/director/lessons'))->with('success', 'Pamoka sėkmingai atnaujinta');
            } else {
                $errors = $this->validator->listErrors();
            }
        } else {
            $errors = 'Klaida';
        }

        return redirect()->to(base_url('/director/lessons'))->with('errors', $errors);
    }

    public function deleteLesson(int $id)
    {
        $lesson = (new LessonModel())->find($id);
        if ($lesson) {
            (new LessonModel())->delete($id);
            (new TeacherModel())
                ->set('lesson_id', 0)
                ->where('lesson_id', $id)
                ->update();

            return redirect()->to(base_url('/director/lessons'))->with('success', 'Pamoka sėkmingai ištrinta');
        } else {
            $errors = 'Klaida';
        }

        return redirect()->to(base_url('/director/lessons'))->with('errors', $errors);
    }

    public function createClass()
    {
        if ($this->validate([
            'title' => 'required|exact_length[2,3]|is_unique[classes.title]',
            'max_week_lessons' => 'required|integer|exact_length[1,2]',
        ])) {
            (new ClassModel())->insert([
                'title' => $this->request->getVar('title'),
                'max_week_lessons' => $this->request->getVar('max_week_lessons'),
            ]);

            return redirect()->to(base_url('/director/classes'))->with('success', 'Klasė sėkmingai sukurta');
        } else {
            return redirect()->to(base_url('/director/classes'))->with('errors', $this->validator->listErrors());
        }
    }



}
