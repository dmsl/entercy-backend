<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Servicecategory;
use Illuminate\Support\Facades\Storage;

class ServicecategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service_category=Servicecategory::get();
        return view('servicecategories.index')->with('service_category',$service_category);
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
        $service_category= Servicecategory::find($id);
        return view('servicecategories.edit')->with('service_category', $service_category);
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
        $service_category= Servicecategory::find($id);

        $service_category->description=$request->input('description');
        $service_category->description_gr=$request->input('description_gr');
        $service_category->description_ru=$request->input('description_ru');
        $service_category->description_it=$request->input('description_it');
        $service_category->description_fr=$request->input('description_fr');
        $service_category->description_ge=$request->input('description_ge');
        $service_category->name_gr=$request->input('name_gr');
        $service_category->name_ru=$request->input('name_ru');
        $service_category->name_it=$request->input('name_it');
        $service_category->name_fr=$request->input('name_fr');
        $service_category->name_ge=$request->input('name_ge');

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
        $path= $request->file('path_img')->storeAs("public/media/ServiceCategories/", $fileNameToStore);

        if ($service_category->path_img!="")
        {
           Storage::delete('public/media/'.$service_category->path_img);
        }

        $service_category->path_img= "ServiceCategories/" . $fileNameToStore;
      }

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
        $path= $request->file('path_thumbnail')->storeAs("public/media/ServiceCategories/", $fileNameToStore);

        if ($service_category->path_thumbnail!="")
        {
           Storage::delete('public/media/'.$service_category->path_thumbnail);
        }

        $service_category->path_thumbnail= "ServiceCategories/" . $fileNameToStore;
      }

      $service_category->save();
      return redirect('/servicecategories')->with('success', 'Service Category updated with success');
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
