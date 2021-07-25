<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to User.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth.home');
    }

    /**
     * Show the application dashboard to Admin.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome()
    {
        return view('auth.adminHome');
    }

    /**
     * Edit attribute from User/Admin.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit()
    {
        $usuario = Auth::user();
        return view('auth.edit', compact('usuario'));
    }

    /**
     * Update attribute from User/Admin.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update_user(Request $request, $id)
    {
        // Validar datos del formulario
        $this->validate($request,[
            'nombre' =>'required|max:50',
            'correo' =>'required|max:100',
        ]);

        $usuario = User::find($id);

        // Comprobar el cambio de contraseña
        if($request->mantener == true){
            // Comprobar que nueva contraseña tenga 6 o mas caracteres
            if(strlen($request->passNew) >= 6){
                // Comprobar si esta bien la contraseña antigua
                if (Hash::check($request->passOld, $usuario->password)) {
                    // Comprobar si contraseña antigua es distinta a la nueva
                    if (!Hash::check($request->passNew, $usuario->password)) {
                        $usuario->password = bcrypt($request->passNew);
                    } else {
                        return back()->with('error', 'No usar la misma contraseña.');
                    }
                } else {
                    return back()->with('error', 'Contraseña Actual no es correcta.');
                }
            } else {
                return back()->with('error', 'La Nueva Contraseña debe tener 6 caracter mínimo.');
            }
        }

        $usuario->name = $request->nombre;
        $usuario->email = $request->correo;
        $usuario->save();

        return redirect()->route('edit_user')->with('success','Datos actualizados con éxito.');
    }


}
