<?php

namespace App\Http\Controllers;

use DB;
use Response;
use App\Models\Course;
use App\Models\Course_Document;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Log;
// use PhpParser\Comment\Doc;

use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\IOException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Defuse\Crypto\File as FileCrypto;
use Defuse\Crypto\Key;
// use Defuse\Crypto\Crypto;

class DocumentController extends Controller
{
    /**
     * Variables Globales.
     *
     */
    private $clave;
    private $allowedMimes;

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->clave = Key::loadFromAsciiSafeString(env("APP_KEY_CRYPTO", "abc"));
        $this->allowedMimes = [
            'application/pdf',
            'application/msword',
            'text/plain',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        // Verificar si existe la carpeta de destino del archivo
        if (!is_dir(public_path("documents\\"))) {
            mkdir(public_path("documents\\"), 0777, true);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::all();
        $courses = Course::all();
        $course_documents = Course_Document::all();

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

        foreach ($documents as $document) {
            // Conversion del tipo de archivo
            for ($i = 0; $i < count($permitidos); $i++) {
                if ($permitidos[$i]['id'] == $document->type) {
                    $document['type_id'] = $permitidos[$i]['type'];
                }
            }

            // Agregar codigo de carreras
            foreach ($course_documents as $pivot) {
                if ($pivot->document == $document->_id) {
                    foreach ($courses as $curso) {
                        if ($pivot->course == $curso->_id) {
                            if ($document['code_course'] === NULL) {
                                $document['code_course'] = $curso->code;
                            } else {
                                $document['code_course'] = $document['code_course'] . ' - ' . $curso->code;
                            }
                        }
                    }
                }
            }
        }

        return view('system.document.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::all();
        return view('system.document.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $time_pre = microtime(true);
        // Validar datos del formulario
        $this->validate($request, [
            'nombre' => 'required',
            'ramos' => 'required|max:8',
            'documentFile' => 'required',
        ]);

        // Verificando si existe archivo
        if ($request->hasFile("documentFile")) {
            $file = $request->file("documentFile");

            $nombre = "file_" . time() . "." . $file->guessExtension();

            $ruta = public_path("documents\\" . $nombre);

            // Verificando si es un tipo permitido
            if (in_array($_FILES['documentFile']['type'], $this->allowedMimes)) {

                // Definiendo las rutas del archivo a encriptar
                $rutaArchivoEntrada = $_FILES['documentFile']['tmp_name'];
                $rutaArchivoSalida = $ruta;

                // Proceso de encriptacion
                try {
                    FileCrypto::encryptFile($rutaArchivoEntrada, $rutaArchivoSalida, $this->clave);
                } catch (IOException $e) {
                    return back()->with('error', 'Error leyendo o escribiendo archivo. Verifica que el archivo de entrada exista y que tengas permiso de escritura');
                } catch (EnvironmentIsBrokenException $e) {
                    return back()->with('error', 'El entorno está roto. Normalmente es porque la plataforma actual no puede encriptar el archivo de una manera segura');
                } catch (WrongKeyOrModifiedCiphertextException $e) {
                    return back()->with('error', 'La clave es errónea o alguien la intentó modificar, cuidado');
                }

                // Creando registro en DB
                $documento = Document::create([
                    'name' => $request->nombre,
                    'type' => $_FILES['documentFile']['type'],
                    'size' => $_FILES['documentFile']['size'],
                    'path' => $ruta,
                    'user' => auth()->user()->_id
                ]);

                // Crea los datos de la tabla pivot
                foreach ($request->ramos as $ramo) {
                    Course_Document::create([
                        'course' => $ramo,
                        'document' => $documento->_id
                    ]);
                }

                // $time_post = microtime(true);
                // $exec_time = $time_post - $time_pre;
                // file_put_contents(public_path("documents\\time.txt"), $exec_time);

                return redirect()->route('document.index')->with('success', 'Documento subido con éxito.');
            } else {
                return back()->with('error', 'NO ES UNA EXTENSIÓN PERMITIDA');
            }
        } else {
            return back()->with('error', 'NO SE ENCONTRO EL ARCHIVO');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        // $time_pre = microtime(true);

        $typefile = explode('.', $document->path);
        $nombre = $document->name . '.' . end($typefile);

        $ruta = public_path("documents\\" . $nombre);

        $rutaArchivoEntrada = $document->path;
        $rutaArchivoSalida = $ruta;

        try {
            // $time_post = microtime(true);
            // $exec_time = $time_post - $time_pre;
            // file_put_contents(public_path("documents\\time.txt"), $exec_time . '\n', FILE_APPEND);

            FileCrypto::decryptFile($rutaArchivoEntrada, $rutaArchivoSalida, $this->clave);
            if ($document->type == 'application/pdf') {
                $response =  Response::make(file_get_contents($rutaArchivoSalida), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $document->name . '"'
                ]);
                unlink($rutaArchivoSalida);
                return $response;
            } else {
                return response()->file($rutaArchivoSalida)->deleteFileAfterSend(true);
            }
        } catch (IOException $e) {
            return back()->with('error', 'Error leyendo o escribiendo archivo. Verifica que el archivo de entrada exista y que tengas permiso de escritura');
        } catch (EnvironmentIsBrokenException $e) {
            return back()->with('error', 'El entorno está roto. Normalmente es porque la plataforma actual no puede desencriptar el archivo de una manera segura');
        } catch (WrongKeyOrModifiedCiphertextException $e) {
            return back()->with('error', 'La clave es errónea o alguien la intentó modificar, cuidado');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document = Document::find($id);
        if ($document->user == auth()->user()->_id || auth()->user()->is_admin == 1) {
            $courses = Course::all();
            $course_documents = Course_Document::all();

            // Agrega Flag a Carreras con curso afectado
            foreach ($courses as $course) {
                foreach ($course_documents as $pivot) {
                    if ($pivot->course == $course->_id && $pivot->document == $document->_id) {
                        $course['flag'] = true;
                    }
                }
            }
            return view('system.document.edit', compact('courses', 'document'));
        } else {
            return back()->with('error', 'No tiene permiso para editar este documento.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validar datos del formulario
        $this->validate($request, [
            'nombre' => 'required',
            'ramos' => 'required|max:8',
        ]);

        $documento = Document::find($id);
        if ($documento->user == auth()->user()->_id || auth()->user()->is_admin == 1) {

            if ($request->mantener != true) {
                // Verificando si existe archivo
                if ($request->hasFile("documentFile")) {
                    $file = $request->file("documentFile");

                    $nombre = "file_" . time() . "." . $file->guessExtension();

                    $ruta = public_path("documents\\" . $nombre);

                    // Verificando si es un tipo permitido
                    if (in_array($_FILES['documentFile']['type'], $this->allowedMimes)) {
                        // Borrar archivo anterior si existe
                        if (File::exists($documento->path)) File::delete($documento->path);

                        // Definiendo las rutas del archivo a encriptar
                        $rutaArchivoEntrada = $_FILES['documentFile']['tmp_name'];
                        $rutaArchivoSalida = $ruta;

                        // Proceso de encriptacion
                        try {
                            FileCrypto::encryptFile($rutaArchivoEntrada, $rutaArchivoSalida, $this->clave);
                        } catch (IOException $e) {
                            return back()->with('error', 'Error leyendo o escribiendo archivo. Verifica que el archivo de entrada exista y que tengas permiso de escritura');
                        } catch (EnvironmentIsBrokenException $e) {
                            return back()->with('error', 'El entorno está roto. Normalmente es porque la plataforma actual no puede encriptar el archivo de una manera segura');
                        } catch (WrongKeyOrModifiedCiphertextException $e) {
                            return back()->with('error', 'La clave es errónea o alguien la intentó modificar, cuidado');
                        }

                        // Reemplazo de datos para BD
                        $documento->type = $_FILES['documentFile']['type'];
                        $documento->size = $_FILES['documentFile']['size'];
                        $documento->path = $ruta;
                    } else {
                        return back()->with('error', 'NO ES UNA EXTENSIÓN PERMITIDA');
                    }
                } else {
                    return back()->with('error', 'NO SE ENCONTRO EL ARCHIVO');
                }
            }

            $documento->name = $request->nombre;
            $documento->save();

            // Borrando datos de tabla pivot
            DB::table('course_documents')->where('document', '=', $documento->_id)->delete();

            // Crea los datos de la tabla pivot
            foreach ($request->ramos as $ramo) {
                Course_Document::create([
                    'course' => $ramo,
                    'document' => $documento->_id
                ]);
            }

            return redirect()->route('document.index')->with('success', 'Documento actualizado con éxito.');
        } else {
            return back()->with('error', 'No tiene permiso para editar este documento.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Se busca el documento a borrar por id, trayendo los datos de la tabla pivot
        $document = Document::find($id);
        if ($document->user == auth()->user()->_id || auth()->user()->is_admin == 1) {

            // Eliminar archivo relacionado
            if (File::exists($document->path)) File::delete($document->path);

            // Borrar datos de tabla pivote antes del documento en si, para no generar errores de FK
            DB::table('course_documents')->where('document', '=', $document->_id)->delete();

            // Se elimina el documento
            $document->delete();

            return back()->with('success', 'Documento eliminado con éxito');
        } else {
            return back()->with('error', 'No tiene permiso para editar este documento.');
        }
    }
}
