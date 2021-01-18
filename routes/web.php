<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin','AdminController@index')->name('admin')->middleware('admin');
Route::get('/teacher','TeacherController@index')->name('teacher')->middleware('teacher');

Route::GET('teacher/exam/create','TeacherController@create_exam_page');
Route::POST('teacher/exam/insert','TeacherController@create_exam_to_course')->name('createExam');
Route::GET('/teacher/exam/edit_view/{exam_id}','TeacherController@get_exam_data');
Route::POST('teacher/exam/edit/{exam_id}','TeacherController@update_exam_of_course');
Route::GET('/teacher/exam/view/{exam_id}','TeacherController@exam_info');

Route::GET('teacher/exam/{exam_id}/question/create','TeacherController@create_question_page')->name('createQuestionPage');
Route::post('teacher/exam/{exam_id}/question/insert/','TeacherController@create_question_to_exam')->name('createQuestion');
Route::GET('/teacher/exam/{exam_id}/question/edit_view/{q_track_id}','TeacherController@get_exam_questions_details')->name('questionEditView');
Route::post('teacher/exam/{exam_id}/question/edit/{q_track_id}','TeacherController@update_question_of_exam')->name('updateQuestion');
Route::delete('teacher/exam/question/delete/{q_track_id}','TeacherController@delete_exam_question')->name('deleteExamQuestion');

// Route::delete('teacher/exam/delete/{exam_id}','TeacherController@delete_exam_of_course')->name('deleteExam');

Route::get('/student','StudentController@index')->name('student')->middleware('student');

Route::get('/student/exam','StudentController@test_exam')->name('exam')->middleware('student');
Route::get('/student/exam/{exam_id}','StudentController@test_exam2')->middleware('student');