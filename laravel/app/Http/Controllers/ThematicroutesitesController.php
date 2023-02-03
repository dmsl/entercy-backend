<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Site;
use App\Thematicroute;
use App\Thematicroutesite;
use DB;

class ThematicroutesitesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function search(Request $request)
    {        
        $keyword=$request->input('keyword');
        $sites= Site::where('name', 'LIKE', "%$keyword%")->get();
        $thematicroute_id= $request->input('thematicroute_id');
        return view('thematicroutesites.create')->with('thematicroute_id', $thematicroute_id)->with('sites', $sites);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function create()
    {
        //
    }*/

    public function create($thematicroute_id)
    {
        $sites= Site::get();
        return view('thematicroutesites.create')->with('thematicroute_id', $thematicroute_id)->with('sites', $sites);
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
        'thematicroute_id' => 'required',
        'siteid' => 'required',        
        ]);

          $thematicroutesite = new Thematicroutesite;
          $thematicroutesite->thematicroute_id=$request->input('thematicroute_id');
          $thematicroutesite->site_id=$request->input('siteid');
          $thematicroutesite->save();

          //----RETURNING TO Thematicroute SHOW PAGE with necessary variables -----          
          $thematicroute= Thematicroute::find($thematicroutesite->thematicroute_id);
                   
          $thematicroutesites = \DB::table('thematicroutesites')
            ->join('sites', 'sites.id', '=', 'thematicroutesites.site_id')
            ->select('sites.name as name', 'thematicroutesites.id')
            ->where('thematicroutesites.thematicroute_id', $thematicroute->id)
            ->get();

          return view('thematicroutes.show')->with('thematicroute',$thematicroute)->with('thematicroutesites',$thematicroutesites);
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
      $thematicroutesite = Thematicroutesite::find($id);
      $thematicroute= Thematicroute::find($thematicroutesite->thematicroute_id);
      $thematicroutesite->delete();
     
      $thematicroutesites = \DB::table('thematicroutesites')
        ->join('sites', 'sites.id', '=', 'thematicroutesites.site_id')
        ->select('sites.name as name', 'thematicroutesites.id')
        ->where('thematicroutesites.thematicroute_id', $thematicroute->id)
        ->get();

      return view('thematicroutes.show')->with('thematicroute',$thematicroute)->with('thematicroutesites',$thematicroutesites);
    }
}
