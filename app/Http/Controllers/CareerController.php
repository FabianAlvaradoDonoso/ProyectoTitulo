<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Career;
use App\Models\Career_Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CareerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $careers = Career::all();
        $career_courses = Career_Course::all();
        return view('system.career.index', compact('careers', 'career_courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->is_admin){
            return view('system.career.create');
        } else {
            return redirect()->route('career.index')->with('error','No tienes las credenciales correspondientes para ejecutar esta acción.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->is_admin){
            $this->validate($request, [
                'nombre'    => 'required',
                'codigo'      => 'required|max:5',
            ]);

            $career = new Career;
            $career->name = $request->nombre;
            $career->code = $request->codigo;
            $career->save();

            // $career->save();
            return redirect()->route('career.index')->with('success', 'Carrera creada con éxito');

        } else {
            return redirect()->route('career.index')->with('error','No tienes las credenciales correspondientes para ejecutar esta acción.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function show(Career $career)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function edit(Career $career)
    {
        if(Auth::user()->is_admin){
            return view('system.career.edit', compact('career'));
        } else {
            return redirect()->route('career.index')->with('error','No tienes las credenciales correspondientes para ejecutar esta acción.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        if(Auth::user()->is_admin){
            $this->validate($request, [
                'nombre'    => 'required',
                'codigo'      => 'required|max:5',
            ]);

            $career = Career::find($id);
            $career->name = $request->nombre;
            $career->code = $request->codigo;
            $career->update();

            return redirect()->route('career.index')->with('success', 'Carrera actualizada con éxito');

        } else {
            return redirect()->route('career.index')->with('error','No tienes las credenciales correspondientes para ejecutar esta acción.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function destroy(Career $career)
    {
        if(Auth::user()->is_admin){
            $career->delete();

            DB::table('career_courses')->where('career', '=', $career->_id)->delete();

            return redirect()->route('career.index')->with('success', 'Carrera eliminada con éxito');
        } else {
            return redirect()->route('career.index')->with('error','No tienes las credenciales correspondientes para ejecutar esta acción.');
        }
    }
}
