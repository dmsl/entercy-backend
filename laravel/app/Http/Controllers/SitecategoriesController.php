<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Sitecategory;

class SitecategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sitecategories= Sitecategory::get();
        return view('sitecategories.index')->with('sitecategories',$sitecategories);
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
        $sitecategories= Sitecategory::find($id);              
        return view('sitecategories.edit')->with('sitecategories', $sitecategories);
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
    $sitecategories = Sitecategory::find($id);

    $sitecategories->description=$request->input('description');
    $sitecategories->description_gr=$request->input('description_gr');
    $sitecategories->description_ru=$request->input('description_ru');
    $sitecategories->description_it=$request->input('description_it');
    $sitecategories->description_fr=$request->input('description_fr');
    $sitecategories->description_ge=$request->input('description_ge');
    $sitecategories->name_gr=$request->input('name_gr');
    $sitecategories->name_ru=$request->input('name_ru');
    $sitecategories->name_it=$request->input('name_it');
    $sitecategories->name_fr=$request->input('name_fr');
    $sitecategories->name_ge=$request->input('name_ge');

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
        $path= $request->file('path')->storeAs("public/media/SiteCategories/", $fileNameToStore);

        if ($sitecategories->path!="")
        {
           Storage::delete('public/media/'.$sitecategories->path);
        }

        $sitecategories->path= "SiteCategories/" . $fileNameToStore;
      }     
      /*if($request->hasFile('path'))
      {
        $sitecategories->path= "SiteCategories/" . $fileNameToStore;
      }*/

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
        $path= $request->file('path_video')->storeAs("public/media/SiteCategories/", $fileNameToStore);

        if ($sitecategories->path_video!="")
        {
           Storage::delete('public/media/'.$sitecategories->path_video);
        }

        $sitecategories->path_video= "SiteCategories/" . $fileNameToStore;
      }  


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
        $path= $request->file('path_thumbnail')->storeAs("public/media/SiteCategories/", $fileNameToStore);

        if ($sitecategories->path_thumbnail!="")
        {
           Storage::delete('public/media/'.$sitecategories->path_thumbnail);
        }

        $sitecategories->path_thumbnail= "SiteCategories/" . $fileNameToStore;
      }  


      $sitecategories->save();
      return redirect('/sitecategories')->with('success', 'Category updated');
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

    public function delete_category_img($id)
    { 
         $sitecategories = Sitecategory::find($id);
         if ($sitecategories->path!="")
        {
           Storage::delete('public/media/'.$sitecategories->path);
           $sitecategories->path= "";
           $sitecategories->save();
        }

        return redirect('/sitecategories')->with('success', "Category's image deleted" );
    }

    public function delete_category_video($id)
    { 
        $sitecategories = Sitecategory::find($id);
         if ($sitecategories->path_video!="")
        {
           Storage::delete('public/media/'.$sitecategories->path_video);
           $sitecategories->path_video= "";
           $sitecategories->save();
        }

        return redirect('/sitecategories')->with('success', "Category's video deleted" );
    }

}
