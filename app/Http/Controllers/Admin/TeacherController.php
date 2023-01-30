<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Course;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'teachers' => Teacher::OrderBy('id', 'desc')->paginate(5)
        ];
        return view('admin.teachers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::All();

        return view('admin.teachers.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $newTeacher = new Teacher();
        $newTeacher->fill($data);
        $newTeacher->save();

        if (array_key_exists('courses', $data)) {
            $newTeacher->courses()->sync($data['courses']);
        }

        return redirect()->route('admin.teachers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $elem = Teacher::findOrFail($id);
        return view('admin.teachers.show', compact('elem'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $elem = Teacher::findOrFail($id);
        $courses = Course::All();
        return view('admin.teachers.edit', compact('elem', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $elem = Teacher::findOrFail($id);

        $elem->update($data);

        if (array_key_exists('courses', $data)) {
            $elem->courses()->sync($data['courses']);
        } else {
            $elem->courses()->sync([]);
        }

        return redirect()->route('admin.teachers.show', $elem->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $elem = Teacher::findOrFail($id);
        $elem->delete();
        return redirect()->route('admin.teachers.index');
    }
}
