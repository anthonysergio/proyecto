<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/administracion';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function username()
    {
    $login = request()->input('email');
    $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'cedula';
    request()->merge([$field => $login]);
    return $field;
    }

    public function login(Request $request) {

        $this->validate($request, [
            'email' => [ 'required' ],
            'password'       => [ 'required' ],
        ]);

        $data = $request; 
        $email = $data['email'];
        $password = $data['password'];
        $usuario = User::where('email',$email)->get();
      
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

          
            
            if (Auth::attempt(['email' => $email, 'password' => $password]))
            {

            
                    return Redirect::to('administracion');

            
                
            }else{
                return Redirect::back()->with('mensaje-error', 'El email o el password están incorrectos, vuelve a intentarlo.');

            }
            
        } else {
           
            if (Auth::attempt(['cedula' => $email, 'password' => $password]))
            {

            
                    return Redirect::to('administracion');

            
                
            }else{
                return Redirect::back()->with('mensaje-error', 'El email o el password están incorrectos, vuelve a intentarlo.');

            }
        }



       



    }

  
}
