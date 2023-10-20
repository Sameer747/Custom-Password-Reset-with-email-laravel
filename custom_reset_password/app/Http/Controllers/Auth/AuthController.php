<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    //login
    public function index()
    {
        return view('auth.login');
    }

    //registration
    public function registration()
    {
        return view('auth.registration');
    }

    //postLogin
    public function postLogin(Request $request)
    {

        //validate request
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        //get credentials
        $credentials = $request->only('email', 'password');

        //attempt auth
        if (Auth::attempt($credentials)) {
            return \redirect()->intended('dashboard')->withSuccess('Login successful');
        } else {
            return redirect("/")->withSuccess('Oppes! You have entered invalid credentials');
        }

    }

    //postRegistration
    public function postRegistration(Request $request)
    {

        //validate request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        //get data
        $data = $request->all();
        //create user
        $check = $this->create($data);
        // dd($check);exit;
        if ($check) {
            return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');
        }
        // return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');

    }

    //dashboard
    public function dashboard()
    {
        if (Auth::check()) {
            return view('dashboard');
        }

        return redirect("/")->withSuccess('Opps! You do not have access');
    }

    //create new user
    public function create(array $data)
    {
        //create a new user
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

    }

    //logout
    public function logout()
    {
        Session::flush();
        Auth::logout();

        return Redirect('/');
    }

}
?>