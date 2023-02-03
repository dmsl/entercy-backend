<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Chronological;

class ChronologicalsController extends Controller
{

    public function api_get_chronologicals()
    {  
        $chronologicals=Chronological::where('parent_id', 0)->get();      
        return response(['status' => 'success', 'data' => $chronologicals]);  
    }
    public function api_get_chronological(Request $request)
    {
        $data=Chronological::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chronologicals=Chronological::where('parent_id', 0)->get();
        return view('chronologicals.index')->with('chronologicals',$chronologicals);
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
         $chronologicals= Chronological::find($id); 
         $sub_chronologicals=Chronological::where('parent_id', $chronologicals->id)->get();             
        return view('chronologicals.edit')->with('chronologicals', $chronologicals)->with('sub_chronologicals', $sub_chronologicals);
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
           //update app content
    $chronologicals = Chronological::find($id);

    $chronologicals->description=$request->input('description');
    $chronologicals->description_gr=$request->input('description_gr');
    $chronologicals->description_ru=$request->input('description_ru');
    $chronologicals->description_it=$request->input('description_it');
    $chronologicals->description_fr=$request->input('description_fr');
    $chronologicals->description_ge=$request->input('description_ge');
    $chronologicals->name_gr=$request->input('name_gr');
    $chronologicals->name_ru=$request->input('name_ru');
    $chronologicals->name_it=$request->input('name_it');
    $chronologicals->name_fr=$request->input('name_fr');
    $chronologicals->name_ge=$request->input('name_ge');

    //handle image file upload
      if($request->hasFile('path_img'))
      {
        //get filename with the extension
        $filenameWithExt=$request->file('path_img')->getClientOriginalName();
        //get just $filename
        $filename= pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just the extension
        $extension= $request->file('path_img')->getClientOriginalExtension();
        //filename to store
        $fileNameToStore=$filename.'_'.time().'.'.$extension;
        //upload image
        $path= $request->file('path_img')->storeAs("public/media/Chronologicals/", $fileNameToStore);

        if ($chronologicals->path_img!="")
        {
           Storage::delete('public/media/'.$chronologicals->path_img);
        }

        $chronologicals->path_img= "Chronologicals/" . $fileNameToStore;
      }
     
    
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
        $path= $request->file('path_video')->storeAs("public/media/Chronologicals/", $fileNameToStore);

        if ($chronologicals->path_video!="")
        {
           Storage::delete('public/media/'.$chronologicals->path_video);
        }

        $chronologicals->path_video= "Chronologicals/" . $fileNameToStore;
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
        $path= $request->file('path_thumbnail')->storeAs("public/media/Chronologicals/", $fileNameToStore);

        if ($chronologicals->path_thumbnail!="")
        {
           Storage::delete('public/media/'.$chronologicals->path_thumbnail);
        }

        $chronologicals->path_thumbnail= "Chronologicals/" . $fileNameToStore;
      }

     
     
      $chronologicals->save();
      return redirect('/chronologicals')->with('success', 'Chronological updated');
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

    public function delete_chronological_img($id)
    { 
         $chronologicals = Chronological::find($id);
         if ($chronologicals->path_img!="")
        {
           Storage::delete('public/media/'.$chronologicals->path_img);
           $chronologicals->path_img= "";
           $chronologicals->save();
        }

        return redirect('/chronologicals')->with('success', "Chronological's image deleted" );
    }

    public function delete_chronological_video($id)
    { 
         $chronologicals = Chronological::find($id);
         if ($chronologicals->path_video!="")
        {
           Storage::delete('public/media/'.$chronologicals->path_video);
           $chronologicals->path_video= "";
           $chronologicals->save();
        }

        return redirect('/chronologicals')->with('success', "Chronological's video deleted" );
    }


}
