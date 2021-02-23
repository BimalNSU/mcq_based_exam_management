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

Route::group(['prefix' => 'teacher',  'middleware' => 'teacher'],function () {
    Route::get('/','TeacherController@index')->name('teacher');
    Route::GET('exam/create','TeacherController@create_exam_page');
    Route::POST('exam/insert','TeacherController@create_exam_to_course')->name('createExam');
    Route::GET('exam/edit_view/{exam_id}','TeacherController@get_exam_data');
    Route::POST('exam/edit/{exam_id}','TeacherController@update_exam_of_course');
    Route::GET('exam/view/{exam_id}','TeacherController@exam_info');
    Route::delete('exam/delete/{exam_id}','TeacherController@delete_exam_of_course')->name('deleteExam');

    Route::GET('exam/{exam_id}/question/create','TeacherController@create_question_page')->name('createQuestionPage');
    Route::post('exam/{exam_id}/question/insert/','TeacherController@create_question_to_exam')->name('createQuestion');
    Route::GET('exam/{exam_id}/question/edit_view/{q_track_id}','TeacherController@get_exam_questions_details')->name('questionEditView');
    Route::post('exam/{exam_id}/question/edit/{q_track_id}','TeacherController@update_question_of_exam')->name('updateQuestion');
    Route::delete('exam/question/delete/{q_track_id}','TeacherController@delete_exam_question')->name('deleteExamQuestion');

});

Route::group(['prefix' => 'student',  'middleware' => 'student'],function () {
    Route::get('/','StudentController@index')->name('student');
    Route::get('exam/view/{exam_id}','StudentController@exam_info');
    Route::get('exam/request/{exam_id}','StudentController@join_request_exam')->name('requestExam');
    Route::get('exam/join/{exam_track_id}','StudentController@join_exam_page')->name('join_exam');
    Route::post('exam/do/{exam_track_id}','StudentController@do_exam')->name('do_exam');
    Route::get('exam/review/{exam_track_id}','StudentController@exam_review')->name('exam_review');
});