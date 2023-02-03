<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Userpreference;
use App\Traveltype_user;

//--------------------
use App\User;
use App\Http\Controllers\Controller;
use App\Useranswer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client as OClient;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
//--------------------

class UserpreferencesController extends Controller
{

    public function api_get_userpreferences_by_userid(Request $request)
    {
        $data = Userpreference::where('user_id', auth('api')->user()->id)->orderBy('created_at', 'DESC')->first();
        return response(['status' => 'success', 'data' => $data]);
    }

    //THE "store_userpreferences" API SHOULD NOT BE USED BY ANYONE ANYMORE
    public function store_userpreferences(Request $request)
    {
        $userPref = new Userpreference;
        $userPref->user_id = auth('api')->user()->id;
        $userPref->districts = $request->input('districts');
        $userPref->arriving_date = $request->input('arriving_date');
        $userPref->departing_date = $request->input('departing_date');
        //$userPref->travel_types=$request->input('travel_types'); //not in use any more
        $userPref->categories = $request->input('categories');
        $userPref->transportation = $request->input('transportation');
        $userPref->save();

        return response(['status' => 'success', 'data' => 'Successfully updated user preferences.']);
    }

    public function get_user_details(Request $request)
    {
        $userID = auth('api')->user()->id;
        $user = User::find($userID);
        $travel_types = Traveltype_user::where('user_id', $userID)->get();

        $myArray = [];
        $myArray[sizeof($myArray)] = array('User' => $user, 'Travel types' => $travel_types);

        return response(['status' => 'success', 'data' => $myArray]);
    }

    public function update_user(Request $request)
    {
        $userID = auth('api')->user()->id;
        $user = User::find($userID);
        $user->gender = $request->input('gender');
        $user->dateOfBirth = $request->input('dateOfBirth');
        $user->country = $request->input('country');
        if (!empty($request->input('name')))
            $user->name = $request->input('name');
        if (!empty($request->input('surname')))
            $user->surname = $request->input('surname');
        //commented out because it was requested that users should not change email addr.
        /*if (!empty($request->input('email'))) {
            if ($request->input('email') == $user->email) {
                //it's the same email so no need to do anything
            } else {
                $this->validate($request, [
                    'email' => ['email', 'max:255', 'unique:users'],
                ]);
                $user->email = $request->input('email');
            }
        }*/
        $user->save();

        if (!empty($request->input('travel_types'))) {
            Traveltype_user::where('user_id', $userID)->delete(); //deletes old records

            $travel_types = $request->input('travel_types');
            $travel_types = explode(',', $travel_types);

            foreach ($travel_types as $travel_type) {
                $traveltype_user = new Traveltype_user;
                $traveltype_user->user_id = auth('api')->user()->id;
                $traveltype_user->traveltype_id = $travel_type;
                $traveltype_user->save();
            }
        }


        return response(['status' => 'success', 'data' => 'Successfully updated user details.']);
    }

    public function is_profile_completed()
    {
        $response = false;
        $userID = auth('api')->user()->id;
        $user = User::find($userID);
        $useranswer = Useranswer::where('user_id', $user->id)->get();
        if (count($useranswer) > 0)
            $response = true;
        else
            $response = false;

        return response(['status' => 'success', 'data' => $response]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
