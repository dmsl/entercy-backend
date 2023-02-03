<?php

namespace App\Http\Controllers;

use App\Qr_room;
use Illuminate\Http\Request;
use App\Qr_room_group;
use App\Qr_room_link;

class Qr_room_groupsController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($qr_room_id)
    {
        return view('qrrooms_groups.create')->with('qr_room_id', $qr_room_id);
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
            'name' => 'required',
          ]);

           $qr_room_group = new Qr_room_group;
           $qr_room_group->name=$request->input('name');

           $qr_room_group->x_position = $request->input('x_position');
           $qr_room_group->y_position = $request->input('y_position');
           $qr_room_group->z_position = $request->input('z_position');

           $qr_room_group->x_scale = $request->input('x_scale');
           $qr_room_group->y_scale = $request->input('y_scale');
           $qr_room_group->z_scale = $request->input('z_scale');

           $qr_room_group->x_rotation = $request->input('x_rotation');
           $qr_room_group->y_rotation = $request->input('y_rotation');
           $qr_room_group->z_rotation = $request->input('z_rotation');

          $qr_room_group->qr_room_id=$request->input('qr_room_id');
          $qr_room_group->save();

        $qr_room=Qr_room::find($qr_room_group->qr_room_id);
        $qr_room_grouped = Qr_room_group::where('qr_room_id', $qr_room->id)->get();
        return view('qrrooms.show')->with('qr_room',$qr_room)->with('qr_room_grouped',$qr_room_grouped);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($qrroom_group_id)
    {
        $qr_room_grouped=Qr_room_group::find($qrroom_group_id);
        $qr_room=Qr_room::find($qr_room_grouped->qr_room_id);
        $qr_room_links = Qr_room_link::where('group_id', $qr_room_grouped->id)->get();
        return view('qrrooms_groups.show')->with('qr_room', $qr_room)->with('qr_room_grouped',$qr_room_grouped)->with('qr_room_links',$qr_room_links);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($qrroom_group_id)
    {
        $qr_room_grouped=Qr_room_group::find($qrroom_group_id);
        $qr_room=Qr_room::find($qr_room_grouped->qr_room_id);
        return view('qrrooms_groups.edit')->with('qr_room', $qr_room)->with('qr_room_grouped',$qr_room_grouped);
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
            'name' => 'required',
          ]);

           $qr_room_group = Qr_room_group::find($id);
           $qr_room_group->name=$request->input('name');
           $qr_room_group->x_position = $request->input('x_position');
           $qr_room_group->y_position = $request->input('y_position');
           $qr_room_group->z_position = $request->input('z_position');

           $qr_room_group->x_scale = $request->input('x_scale');
           $qr_room_group->y_scale = $request->input('y_scale');
           $qr_room_group->z_scale = $request->input('z_scale');

           $qr_room_group->x_rotation = $request->input('x_rotation');
           $qr_room_group->y_rotation = $request->input('y_rotation');
           $qr_room_group->z_rotation = $request->input('z_rotation');

          $qr_room_group->qr_room_id=$request->input('qr_room_id');
          $qr_room_group->save();

        $qr_room=Qr_room::find($qr_room_group->qr_room_id);
        $qr_room_grouped = Qr_room_group::where('qr_room_id', $qr_room->id)->get();
        return view('qrrooms.show')->with('qr_room',$qr_room)->with('qr_room_grouped',$qr_room_grouped);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($qr_room_groupid)
    {
        $qr_room_group = Qr_room_group::find($qr_room_groupid);
        $qr_room= Qr_room::find($qr_room_group->qr_room_id);

        $qr_rooms_links=Qr_room_link::where('group_id',$qr_room_groupid)->delete();
        $qr_room_group->delete();

        $qr_room_grouped = Qr_room_group::where('qr_room_id', $qr_room->id)->get();

        return view('qrrooms.show')->with('qr_room',$qr_room)->with('qr_room_grouped',$qr_room_grouped);
    }
}
