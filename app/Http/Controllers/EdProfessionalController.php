<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EdProfessional extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profesionals = EdProfessional::latest()->paginate(5);

        return view('edprofessional.index', compact('profesionals'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('edprofessional.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'prof_first_name' => 'required',
            'prof_last_name' => 'required',
            'prof_address' => 'required',
            'prof_phone' => 'required',
            'prof_mail' => 'required'
        ]);

        EdProfessional::create($request->all());

        return redirect()->route('edprofessional.index')
            ->with('success', 'Profesional medico creado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(EdProfessional $professional)
    {
        return view('edprofessional.show', compact('professional'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(EdProfessional $professional)
    {
        return view('edprofessional.edit', compact('professional'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EdProfessional $professional)
    {
        $request->validate([
            'prof_first_name' => 'required',
            'prof_last_name' => 'required',
            'prof_address' => 'required',
            'prof_phone' => 'required',
            'prof_mail' => 'required'
        ]);

        $professional->update($request->all());

        return redirect()->route('edprofessional.index')
            ->with('success', 'Profesional actualizado correctamente');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(EdProfessional $professional)
    {
        $professional->delete();

        return redirect()->route('edprofessional.index')
            ->with('success', 'Profesional borrado correctamente');
    }
}
