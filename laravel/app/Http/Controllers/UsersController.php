<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use DB;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UsersController extends Controller
{
    /** REMEMBER TO ADD to prevent unauthorised users to create/view/edit data
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    public function api_getusers()
    {
        return response(['status' => 'success', 'data' => User::all()]);
        //return response()->json(User::all(), 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
        //return User::all();
    }

    public function api_getuser(Request $request)
    {
        $data=User::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
        //return response()->json(User::find($id), 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //return User::all(); //change in what you need
      $users= User::orderBy('created_at','desc')->paginate(10);
      return view('users.index')->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
      $this->validate($request, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'surname' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255'],
        'role' => 'required',
      ]);

      //create Site
      $user = new User;
      $user->name=$request->input('name');
      $user->surname=$request->input('surname');
      $user->username=$request->input('username');
      $user->email=$request->input('email');
      $user->password=Hash::make($request->input('password'));
      $user->role=$request->input('role');
      $user->gender=$request->input('gender');
      $user->dateOfBirth=$request->input('dob');
      $user->country=$request->input('country');
      $user->save();

      return redirect('/users')->with('success', 'User created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $user= User::find($id);
      return view('users.show')->with('user',$user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $user= User::find($id);
      return view('users.edit')->with('user',$user);
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
        $user = User::find($id);
        //return $request->input('name');

        if ($request->input('email') == $user->email)
        {
          $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'surname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'role' => 'required',
          ]);

          $user->name=$request->input('name');
          $user->surname=$request->input('surname');
          $user->username=$request->input('username');
          if ($request->input('password') != '*********')
          {
              $user->password=Hash::make($request->input('password'));
          }
          $user->role=$request->input('role');
          $user->gender=$request->input('gender');
          $user->dateOfBirth=$request->input('dob');
          $user->country=$request->input('country');
          $user->save();

        }
        else
        {
          $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'surname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'role' => 'required',
          ]);

          $user->name=$request->input('name');
          $user->surname=$request->input('surname');
          $user->username=$request->input('username');
          $user->email=$request->input('email');
          if ($request->input('password') != '*********')
          {
              $user->password=Hash::make($request->input('password'));
          }
          $user->role=$request->input('role');
          $user->gender=$request->input('gender');
          $user->dateOfBirth=$request->input('dob');
          $user->country=$request->input('country');
          $user->save();
        }

        //return redirect('/users')->with('success', 'User updated!');
        $user= User::find($id);
        return view('users.show')->with('user',$user)->with('success', 'User updated!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $user = User::find($id);
      $user->delete();
      return redirect('/users')->with('success', 'User Removed!');
    }
}
