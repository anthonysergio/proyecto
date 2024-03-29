<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Facades\Hash;
use App\Rol;
use App\UserRol;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UserRequest;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::where('estado',1)->orderBy('id')->get();
 
       
        return View('administracion.usuarios.index',compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Rol::where('estado',1)->orderBy('id')->get();
        
        return view('administracion.usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
      
       
        $imagen = $request->file('path');  /** obtener la imagenes del campo path*/
        $imagen->move(public_path('imagenes'), $imagen->getClientOriginalName()); /** mover la imagen a la carpeta imagenes y obtener el nombre  */
        

       $usuario= User::create([
            'name' =>  $request->name,
            'apellido' =>  $request->apellido,
            'cedula' =>  $request->cedula,
            'edad' =>  $request->edad,
            'path' =>  $imagen->getClientOriginalName(),
            'email' =>  $request->email,
            'password' =>  Hash::make($request->password),
      
        
        ]);

        $total_roles = $request->roles;
        //si tiene roles
            if (isset($total_roles )) {
                foreach($total_roles as $roles){
                    UserRol::create([
                        'user_id'=>$usuario->id,
                        'rol_id'=>$roles,
                    ]);
                }
        

            }

        return Redirect::to('administracion/usuario')->with('mensaje-registro', 'Se registró Correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $user = User::find($id);
        $user->estado = 0;
        $user->save();

        DB::table('user_rol')->where('user_id', $user->id)->delete();

        $message = "Eliminado Correctamente";
        if ($request->ajax()) {
            return response()->json([
               
                'message' => $message
            ]);
        }
    }
 
    public function ajax(Request $request)
    {


      
              

            $usuarios = User::where('estado',1)->get();
           // $roles=DB::select("select * from where edtado=1");
            return Datatables::of($usuarios)
                    ->addIndexColumn()
                    ->setRowId('id')
              
                    ->addColumn('accion', function($row){

                        $url = route('usuario.edit',['parameters' => $row->id]);
   
                           $btn = '<a title="Editar" style="cursor:pointer;" href="'.$url. '" role="button"><i class="fa fa-edit"></i></a>
                            <a title="Eliminar" style="cursor:pointer;"   onclick="eliminar_usuario('.$row->id.')" class="btn-delete" role="button"><i class="fa fa-trash"></i></a>';
     
                            return $btn;
                    })
                    ->rawColumns(['accion'])
                    ->make(true);
        
      
      
    }

    public function buscar_cedula_usuario(Request $request)
    {
       if($request->ajax()){
          
           $cedula=$request->cedula;
           $usuarios = User::where('cedula', $cedula)->get();

              if(count($usuarios) > 0) {

   
                 
                   return response()->json("existe");
    
                }else{

            
                 

                 return response()->json("no_existe");


                }
           } 


       }

       public function buscar_email_usuario(Request $request)
       {
   
           if($request->ajax()){
             
               $email=$request->email;

               $usuarios= User::where('email', $email)->get();
               if(count($usuarios) > 0) {
                return response()->json("existe");
    
               }else{
              
                return response()->json("no_existe");
    
               }
    
               
    
           }
   
   
       
   
       }
    

       
    public function cargar_roles(Request $request)
    {
        $search = $request->get('search');

        $data = DB::table('roles')
        ->where('roles.rol', 'like', $search.'%')
        ->where('roles.estado', '=', 1)
        ->paginate(5);

       

        //$data=  DB::select(" select id, nombre from grupocontactos where nombre like  ? and estado=1 and id in (select grupo_id from contacto_grupo_contacto where contacto_id in (select id from contactos where id_user = ?))", ['"%' . $search . '%"', Auth::user()->id] );
       // $data = GrupoContacto::select(['id', 'nombres_apellidos'])->where('nombres_apellidos', 'like', '%' . $search . '%')->where('estado', 1)->where('activo',1)->orderBy('nombres_apellidos')->where('id_user', Auth::user()->id)->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}
