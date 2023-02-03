<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Cy_district;

class Cy_districtsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cydistricts= Cy_district::get();
        return view('cydistricts.index')->with('cydistricts',$cydistricts);
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
      $cydistricts= Cy_district::find($id);              
      return view('cydistricts.edit')->with('cydistricts', $cydistricts);
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
        
    //update Distict
    $cydistricts = Cy_district::find($id);

    $cydistricts->description=$request->input('description');
    $cydistricts->description_gr=$request->input('description_gr');
    $cydistricts->description_ru=$request->input('description_ru');
    $cydistricts->description_it=$request->input('description_it');
    $cydistricts->description_fr=$request->input('description_fr');
    $cydistricts->description_ge=$request->input('description_ge');
    $cydistricts->name_gr=$request->input('name_gr');
    $cydistricts->name_ru=$request->input('name_ru');
    $cydistricts->name_it=$request->input('name_it');
    $cydistricts->name_fr=$request->input('name_fr');
    $cydistricts->name_ge=$request->input('name_ge');

    //handle image file upload
      if($request->hasFile('path'))
      {
        //get filename with the extension
        $filenameWithExt=$request->file('path')->getClientOriginalName();
        //get just $filename
        $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just the extension
        $extension= $request->file('path')->getClientOriginalExtension();
        //filename to store
        $fileNameToStore=$filename.'_'.time().'.'.$extension;
        //upload image
        $path= $request->file('path')->storeAs("public/media/CyDistricts/", $fileNameToStore);

        if ($cydistricts->path!="")
        {
           Storage::delete('public/media/'.$cydistricts->path);
        }

        $cydistricts->path= "CyDistricts/" . $fileNameToStore;
      }
     
      /*if($request->hasFile('path'))
      {
        $cydistricts->path= "CyDistricts/" . $fileNameToStore;
      }*/

      //---------------------------------------------------------------------------------
      //handle video file upload
      if($request->hasFile('path_video'))
      {
        //get filename with the extension
        $filenameWithExt=$request->file('path_video')->getClientOriginalName();
        //get just $filename
        $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just the extension
        $extension= $request->file('path_video')->getClientOriginalExtension();
        //filename to store
        $fileNameToStore=$filename.'_'.time().'.'.$extension;
        //upload image
        $path= $request->file('path_video')->storeAs("public/media/CyDistricts/", $fileNameToStore);

        if ($cydistricts->path_video!="")
        {
           Storage::delete('public/media/'.$cydistricts->path_video);
        }

        $cydistricts->path_video= "CyDistricts/" . $fileNameToStore;
      }

       //---------------------------------------------------------------------------------
      //handle thumbnail file upload
      if($request->hasFile('path_thumbnail'))
      {
        //get filename with the extension
        $filenameWithExt=$request->file('path_thumbnail')->getClientOriginalName();
        //get just $filename
        $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just the extension
        $extension= $request->file('path_thumbnail')->getClientOriginalExtension();
        //filename to store
        $fileNameToStore=$filename.'_thumbnail_'.time().'.'.$extension;
        //upload image
        $path= $request->file('path_thumbnail')->storeAs("public/media/CyDistricts/", $fileNameToStore);

        if ($cydistricts->path_thumbnail!="")
        {
           Storage::delete('public/media/'.$cydistricts->path_thumbnail);
        }

        $cydistricts->path_thumbnail= "CyDistricts/" . $fileNameToStore;
      }
     
      /*if($request->hasFile('path_video'))
      {
        $cydistricts->path_video= "CyDistricts/" . $fileNameToStore;
      }*/

      $cydistricts->save();
      return redirect('/cydistricts')->with('success', 'District updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         
    }

    public function delete_district_img($id)
    { 
         $cydistricts = Cy_district::find($id);
         if ($cydistricts->path!="")
        {
           Storage::delete('public/media/'.$cydistricts->path);
           $cydistricts->path= "";
           $cydistricts->save();
        }

        return redirect('/cydistricts')->with('success', "District's image deleted" );
    }

    public function delete_district_video($id)
    { 
         $cydistricts = Cy_district::find($id);
         if ($cydistricts->path_video!="")
        {
           Storage::delete('public/media/'.$cydistricts->path_video);
           $cydistricts->path_video= "";
           $cydistricts->save();
        }

        return redirect('/cydistricts')->with('success', "District's video deleted" );
    }
}
