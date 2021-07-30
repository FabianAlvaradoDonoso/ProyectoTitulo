<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Course;
use App\Models\Career;
use App\Models\Career_Course;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PhpParser\Node\Expr\Cast\Array_;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'showDocuments']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::all();
        $career_courses = Career_Course::all();
        return view('system.course.index', compact('courses', 'career_courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->is_admin){
            $careers = Career::all();
            return view('system.course.create', compact('careers'));
        } else {
            return redirect()->route('course.index')->with('error','No tienes las credenciales correspondientes para ejecutar esta acción.');
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
            // Validar datos del formulario
            $this->validate($request,[
                'nombre' =>'required',
                'codigo' =>'required|max:8',
                'carreras' =>'required',
            ]);

            // Crea Curso
            $course = Course::create([
                'name' => $request->nombre,
                'code' => $request->codigo,
            ]);

            // Crea los datos de la tabla pivot
            foreach($request->carreras as $carrera){
                Career_Course::create([
                    'career' => $carrera,
                    'course' => $course->_id
                ]);
            }

            return redirect()->route('course.index')->with('success','Ramo creado con éxito.');
        } else {
            return redirect()->route('course.index')->with('error','No tienes las credenciales correspondientes para ejecutar esta accion.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->is_admin){
            $course = Course::find($id);
            $career_courses = Career_Course::all();
            $careers = Career::all();

            // Agrega Flag a Carreras con curso afectado
            foreach ($careers as $career ) {
                foreach ($career_courses as $pivot) {
                    if($pivot->course == $course->_id && $pivot->career == $career->_id){
                        $career['flag'] = true;
                    }
                }
            }
            return view('system.course.edit', compact('course', 'careers'));
        } else {
            return redirect()->route('course.index')->with('error','No tienes las credenciales correspondientes para ejecutar esta acción.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Auth::user()->is_admin){
            $this->validate($request,[
                'nombre' =>'required',
                'codigo' =>'required|max:8',
                'carreras' =>'required',
            ]);

            // Actualizar datos de las carreras
            $course = Course::find($id);
            $course->name = $request->nombre;
            $course->code = $request->codigo;
            $course->save();

            // Borrando datos de tabla pivot
            DB::table('career_courses')->where('course', '=', $course->_id)->delete();

            // Colocando nuevos datos en tabla pivot
            foreach($request->carreras as $carrera){
                Career_Course::create([
                    'career' => $carrera,
                    'course' => $course->_id
                ]);
            }

            return redirect()->route('course.index')->with('success','Ramo modificado con éxito.');

        } else {
            return redirect()->route('course.index')->with('error','No tienes las credenciales correspondientes para ejecutar esta acción.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->is_admin){
            // Se busca el ramo a borrar por id, trayendo los datos de la tabla pivot
            $course = Course::find($id);

            // Borrar datos de tabla pivote con las carreras antes del ramo en si, para no generar errores de FK
            DB::table('career_courses')->where('course', '=', $course->_id)->delete();

            // Borrar datos de tabla pivote con los documentos antes del ramo en si, para no generar errores de FK
            $course_documents = DB::table('course_documents')->where('course', '=', $course->_id)->get();
            foreach ($course_documents as $pivot ) {
                $document = Document::find($pivot['document']);
                // Eliminar archivo relacionado
                if(File::exists($document->path)) File::delete($document->path);

                // Borrar datos de tabla pivote antes del documento en si, para no generar errores de FK
                DB::table('course_documents')->where('course', '=', $course->_id)->delete();

                // Se elimina el documento
                $document->delete();
            }

            // Se elimina el ramo
            $course->delete();

            return back()->with('success', 'Ramo eliminado con éxito');

        } else {
            return redirect()->route('course.index')->with('error','No tienes las credenciales correspondientes para ejecutar esta acción.');
        }
    }

    public function showDocuments($id)
    {
        // mostrar todos los documentos de ese curso

        $course_documents = DB::table('course_documents')->where('course', '=', $id)->get();
        $course = Course::find($id);
        $documentos = Array();
        $permitidos = array(
            array(
                "id" => "application/pdf",
                "type" => "PDF"
            ),
            array(
                "id" => "application/msword",
                "type" => "WORD"
            ),
            array(
                "id" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "type" => "WORD"
            ),
            array(
                "id" => "text/plain",
                "type" => "TXT"
            )
        );
        foreach($course_documents as $documents){
            $document = Document::find($documents['document']);

            for ($i = 0; $i < count($permitidos); $i++) {
                if ($permitidos[$i]['id'] == $document->type) {
                    $document['type_id'] = $permitidos[$i]['type'];
                }
            }
            array_push($documentos, $document);
        }
        // dd($documentos);
        return view('system.course.showDocuments', compact('documentos', 'course'));
    }
}
