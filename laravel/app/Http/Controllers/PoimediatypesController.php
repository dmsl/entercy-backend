<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Poimediatype;

class PoimediatypesController extends Controller
{

    public function api_getmediatypes()
    {        
        $poimediatypes= DB::select("SELECT *,
                                    CASE 
                                        WHEN name like 'Image File' then 'Image File'
                                        WHEN name like 'Video File' then 'Video File'
                                        WHEN name like 'Zip File-3D Model' then '3D Model'
                                        WHEN name like '360 Tour' then '360 Tour'
                                        WHEN name like '360 Tour VR' then '360 Tour VR'
                                        WHEN name like '360 single (Image)' then '360 single'    
                                    END AS display_name
                                    FROM `poimediatypes` 
                                    WHERE name not like  '%description%' AND name not like  '%POI Cover Image%'");
        
        return response(['status' => 'success', 'data' => $poimediatypes]);  
    }

    public function api_getmediatype(Request $request)
    {
        $poimediatypes= DB::select("SELECT *,
                                    CASE 
                                        WHEN name like 'Image File' then 'Image File'
                                        WHEN name like 'Video File' then 'Video File'
                                        WHEN name like 'Zip File-3D Model' then '3D Model'
                                        WHEN name like '360 Tour' then '360 Tour'
                                        WHEN name like '360 Tour VR' then '360 Tour VR'
                                        WHEN name like '360 single (Image)' then '360 single'    
                                    END AS display_name
                                    FROM `poimediatypes` 
                                    WHERE name not like  '%description%' AND name not like  '%POI Cover Image%' AND id=".$request->input('id'));
        
        return response(['status' => 'success', 'data' => $poimediatypes]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $poimediatypes= Poimediatype::get();
        return view('poimediatypes.index')->with('poimediatypes',$poimediatypes);
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
        $poimediatypes= Poimediatype::find($id);              
        return view('poimediatypes.edit')->with('poimediatypes', $poimediatypes);
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
         //update media type
    $poimediatypes = Poimediatype::find($id);

    $poimediatypes->description=$request->input('description');
    $poimediatypes->description_gr=$request->input('description_gr');
    $poimediatypes->description_ru=$request->input('description_ru');
    $poimediatypes->description_it=$request->input('description_it');
    $poimediatypes->description_fr=$request->input('description_fr');
    $poimediatypes->description_ge=$request->input('description_ge');
    $poimediatypes->name_gr=$request->input('name_gr');
    $poimediatypes->name_ru=$request->input('name_ru');
    $poimediatypes->name_it=$request->input('name_it');
    $poimediatypes->name_fr=$request->input('name_fr');
    $poimediatypes->name_ge=$request->input('name_ge');

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
        $path= $request->file('path_img')->storeAs("public/media/MediaTypes/", $fileNameToStore);

        if ($poimediatypes->path_img!="")
        {
           Storage::delete('public/media/'.$poimediatypes->path_img);
        }

        $poimediatypes->path_img= "MediaTypes/" . $fileNameToStore;
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
        $path= $request->file('path_thumbnail')->storeAs("public/media/MediaTypes/", $fileNameToStore);

        if ($poimediatypes->path_thumbnail!="")
        {
           Storage::delete('public/media/'.$poimediatypes->path_thumbnail);
        }

        $poimediatypes->path_thumbnail= "MediaTypes/" . $fileNameToStore;
      }
     
      $poimediatypes->save();
      return redirect('/poimediatypes')->with('success', 'Media Type updated');
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
