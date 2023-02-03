<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Poitag;

class TagsController extends Controller
{

	public function api_gettags()
    {
      return response(['status' => 'success', 'data' => Tag::all()]);
    }

    public function api_gettag(Request $request)
    {
        $data=Tag::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags= Tag::orderBy('created_at','desc')->paginate(10);
        return view('tags.index')->with('tags',$tags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tags.create');
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
        'tagcode' => 'required',
        'tagname' => 'required',        
      ]);

      //create Site
      $tag = new Tag;
      $tag->tag_code=$request->input('tagcode');
      $tag->tag_name=$request->input('tagname');
      $tag->save();

      return redirect('/tags')->with('success', 'Tag created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag= Tag::find($id);
        return view('tags.show')->with('tag',$tag);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $tag= Tag::find($id);
      return view('tags.edit')->with('tag',$tag);
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
        $this->validate($request, [
        'tagcode' => 'required',
        'tagname' => 'required',        
      ]);

      //create Site
      $tag = Tag::find($id);
      $tag->tag_code=$request->input('tagcode');
      $tag->tag_name=$request->input('tagname');
      $tag->save();

      return redirect('/tags')->with('success', 'Tag updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Poitag::where('tag_id', $id )->delete(); //if any exist on POI-Tags delete it along with the original one

        $tag = Tag::find($id);
        $tag->delete();
        return redirect('/tags')->with('success', 'Tag Removed!');
    }
}
