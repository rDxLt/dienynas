<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TeacherModel;
use App\Models\ClassModel;
use App\Models\ScheduleModel;
use App\Models\StudentModel;
use App\Models\GradeModel;
use App\Models\AttendanceModel;
use App\Models\NoticeModel;

class Teacher extends BaseController
{
    public function __construct()
    {
        if (session()->user['type'] != 'teacher') {
            dd('galima tik mokytojui');
        }
    }

    public function index(string $date = null)
    {
        $teacher = (new TeacherModel())->where('user_id', session()->user['id'])->first();

        $data = [
            'days' => ScheduleModel::DAYS,
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
            'teacher_schedule' => (new ScheduleModel())->getTeacherLessons($teacher['id'], $date),
        ];

        if ($date != null) {
            $data['date'] = $date;
        }

        if ($teacher['class_id'] != 0) {
            $data['schedule'] = ScheduleModel::getLessons($teacher['class_id']);
            $data['students'] = (new ClassModel())->getStudents($teacher['class_id']);
            $data['class'] = (new ClassModel())->find($teacher['class_id']);
            $data['count_lessons'] = (new ScheduleModel())->where('class_id', $teacher['class_id'])->countAllResults();
            $data['teachers'] = (new TeacherModel())
                ->select('teachers.id, users.email, users.firstname, users.lastname, lessons.title as lesson')
                ->join('users', 'users.id = teachers.user_id')
                ->join('lessons', 'lessons.id = teachers.lesson_id', 'left')
                ->where('lesson_id !=', 0)
                ->findAll();

        }

        return view('users/teacher/home', $data);
    }

    public function addLesson()
    {
        if ($this->validate([
            'week_day' => 'required|in_list[' . implode(',', ScheduleModel::DAYS) . ']',
            'lesson_number' => 'required|integer|exact_length[1]',
            'teacher_id' => 'required|is_not_unique[teachers.id]',
            'cabinet' => 'required|string|min_length[1]|max_length[30]',
        ])) {
            $schedule = (new ScheduleModel())
                ->where('class_id', (new TeacherModel())->where('user_id', session()->user['id'])->first()['class_id'])
                ->where('week_day', $this->request->getVar('week_day'))
                ->where('lesson_number', $this->request->getVar('lesson_number'))
                ->first();
            if (!$schedule) {
                $user = (new TeacherModel())->where('user_id', session()->user['id'])->first();
                $class_id = $user['class_id'];
                $teacher = (new TeacherModel())->where('id', $this->request->getVar('teacher_id'))->first();

                $schedule_data = [
                    'class_id' => $class_id,
                    'lesson_number' => $this->request->getVar('lesson_number'),
                    'lesson_id' => $teacher['lesson_id'],
                    'teacher_id' => $teacher['id'],
                    'cabinet' => $this->request->getVar('cabinet'),
                    'week_day' => $this->request->getVar('week_day'),
                ];

                (new ScheduleModel)->insert($schedule_data);

                return redirect()->to(base_url('/teacher/index'))->with('success', 'Pamoka sėkmingai pridėta prie tvarkaraščio');
            } else {
                $errors = 'Laikas užimtas';
            }
        } else {
            $errors = $this->validator->listErrors();
        }

        return redirect()->to(base_url('/teacher/index'))->with('errors', $errors);
    }

    public function removeLesson(int $schedule_id)
    {
        $schedule = (new ScheduleModel())->find($schedule_id);
        if ($schedule) {
            (new ScheduleModel())->delete($schedule_id);

            return redirect()->to(base_url('/teacher/index'))->with('success', 'Pamoka sėkimingai ištrinta');

        } else {
            $errors = 'Klaida';
        }

        return redirect()->to(base_url('/teacher/index'))->with('errors', $errors);
    }

    public function date()
    {
        if ($this->validate([
            'date' => 'required|valid_date[Y-m-d]',
        ])) {
            $date = $this->request->getVar('date');
            return redirect()->to(base_url('/teacher/index/' . $date));

        }

        return redirect()->to(base_url('/teacher/index'))->with('errors', 'Bloga data');
    }

    public function lesson(int $schedule_id, string $date)
    {
        $teacher = (new TeacherModel())
            ->select('teachers.*, lessons.title, lessons.id as lesson_id')
            ->join('lessons', 'lessons.id = teachers.lesson_id')
            ->where('teachers.user_id', session()->user['id'])
            ->first();
        $schedule = (new ScheduleModel())
            ->select('classes.title, schedules.cabinet, schedules.class_id, schedules.id')
            ->join('classes', 'classes.id = schedules.class_id')
            ->where('schedules.week_day', strtolower(date('l', strtotime($date))))
            ->where('schedules.teacher_id', $teacher['id'])
            ->where('schedules.id', $schedule_id)
            ->first();
        if ($schedule) {
            $students = (new StudentModel())
                ->select('students.id, users.firstname, users.lastname, grades.grade, attendance.status as attendance, notices.message, notices.status as message_status')
                ->join('users', 'users.id = students.user_id')
                ->join('attendance', 'attendance.student_id = students.id and attendance.date = "' . $date . '" and attendance.teacher_id = ' . $teacher['id'], 'left')
                ->join('grades', 'grades.student_id = students.id and grades.date = "' . $date . '" and grades.teacher_id = ' . $teacher['id'], 'left')
                ->join('notices', 'notices.student_id = students.id and notices.date = "' . $date . '" and notices.teacher_id = ' . $teacher['id'], 'left')
                ->where('students.class_id', $schedule['class_id'])
                ->findAll();
            $data = [
                'schedule' => $schedule,
                'students' => $students,
                'teacher' => $teacher,
                'date' => $date,
            ];

            return view('users/teacher/lesson', $data);
        }

        return redirect()->to(base_url('/teacher/index'))->with('errors', 'Klaida');
    }

    public function saveLesson(int $schedule_id, string $date)
    {
        $teacher = (new TeacherModel())
            ->select('teachers.*, lessons.title')
            ->join('lessons', 'lessons.id = teachers.lesson_id')
            ->where('teachers.user_id', session()->user['id'])
            ->first();

        $schedule = (new ScheduleModel())
            ->select('classes.title, schedules.cabinet, schedules.class_id, schedules.lesson_id')
            ->join('classes', 'classes.id = schedules.class_id')
            ->where('schedules.week_day', strtolower(date('l', strtotime($date))))
            ->where('schedules.teacher_id', $teacher['id'])
            ->where('schedules.id', $schedule_id)
            ->first();

        if (!$schedule) {
            dd('klaida');
        }

        $items = $this->request->getVar('content');
        foreach ($items as $student_id => $content) {
            if (empty($content)) {
                continue;
            }
            $content = strtolower($content);

            $data = [
                'teacher_id' => $teacher['id'],
                'lesson_id' => $schedule['lesson_id'],
                'student_id' => $student_id,
                'date' => $date,
            ];

            if (is_numeric($content) && in_array($content, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10])) {
                $grade = (new GradeModel());
                foreach ($data as $key => $value) {
                    $grade = $grade->where($key, $value);
                }
                $grade = $grade->first();

                if ($grade) {
                    (new GradeModel())
                        ->set('grade', $content)
                        ->where('id', $grade['id'])
                        ->update();
                } else {
                    (new GradeModel())->insert(
                        array_merge($data, [
                            'grade' => $content
                        ])
                    );
                }
            } else if (in_array($content, ['p', 'pavelavo', 'n', 'nera', 'nebuvo'])) {
                $attendance = (new AttendanceModel());
                foreach ($data as $key => $value) {
                    $attendance = $attendance->where($key, $value);
                }
                $attendance = $attendance->first();

                if (in_array($content, ['p', 'pavelavo'])) {
                    $status = 'late';
                } elseif (in_array($content, ['n', 'nera', 'nebuvo'])) {
                    $status = 'missing';
                }

                if ($attendance) {
                    (new AttendanceModel())
                        ->set('status', $status)
                        ->where('id', $attendance['id'])
                        ->update();
                } else {
                    (new AttendanceModel())->insert(
                        array_merge($data, [
                            'status' => $status
                        ])
                    );
                }
            } else {
                $badWords = ['neklause', 'nesimoke', 'isdikavo', 'musesi', 'blogas'];
                $goodWords = ['geras', 'atidus', 'kruopstus', 'stengiasi'];
                $words = str_word_count($content, 1);

                $badWords = count(array_intersect($badWords, $words));
                $goodWords = count(array_intersect($goodWords, $words));

                $notice = (new NoticeModel());
                foreach ($data as $key => $value) {
                    $notice = $notice->where($key, $value);
                }
                $notice = $notice->first();

                if ($badWords > $goodWords) {
                    $status = 'negative';
                } elseif ($badWords < $goodWords) {
                    $status = 'positive';
                } else {
                    $status = 'other';
                }

                if ($notice) {
                    (new NoticeModel())
                        ->set('message', $content)
                        ->set('status', $status)
                        ->where('id', $notice['id'])
                        ->update();
                } else {
                    (new NoticeModel())->insert(
                        array_merge($data, [
                            'message' => $content,
                            'status' => $status
                        ])
                    );
                }
            }
        }


        return redirect()->to(base_url('/teacher/index'))->with('success', 'Pamoka sėkmingai užpildyta');
    }
}
