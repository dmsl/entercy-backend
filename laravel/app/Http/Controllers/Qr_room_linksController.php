<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Qr_room_link;
use App\Qr_room;
use App\Poi;
use App\Poimedia;
use App\Qr_room_group;
use App\Storytelling;
use Illuminate\Support\Facades\Validator;

class Qr_room_linksController extends Controller
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


    public function api_get_group_links(Request $request){
        //input parameter siteid kai group_id kai type
        //output qrromlinks+mediatypes
      $qrroom_group_id=$request->input('group_id');
      $site_id=$request->input('site_id');
      $qr_room_links = DB::table('qr_room_links')
            ->join('poimedia', 'qr_room_links.poi_media_id', '=', 'poimedia.id')
            ->join('poimediatypes', 'poimedia.type', '=', 'poimediatypes.id')
            ->select('qr_room_links.*','poimediatypes.id as type_id','poimediatypes.name as type_name', 'poimedia.uri')
            ->where('group_id', $qrroom_group_id)
            ->get();

    $room_links=[];
     foreach ($qr_room_links as $room_link) {
          array_push($room_links, $room_link);
        }

      return response(['status' => 'success', 'qr_room_links' => $room_links]);
    }

    public function create($qrroom_group_id)
    {
        $qrroom_group = Qr_room_group::find($qrroom_group_id);
        $qrroom = Qr_room::find($qrroom_group->qr_room_id);
        $poi = Poi::find($qrroom->poi_id);
        $tempTable = [$poi->id];
        $isZero = false;
        $poimedia_merged = [];
        $poimedia_DONT_include = [];
        $storytelling_merged = [];
        $storytelling_DONT_include = [];
        while ($isZero == false) {
            $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
            $poimedia = Poimedia::where('poi_id', $tempTable[0])->get();
            $storytellings = Storytelling::where('poi_id', $tempTable[0])->get();
            $temp_qrrooms = Qr_room::where('poi_id', $tempTable[0])->get();
            foreach ($poimedia as $poi_media) {
                foreach ($temp_qrrooms as $temp_qrroom) {
                    $temp_qrroom_links = Qr_room_link::where('qr_room_id', $temp_qrroom->id)->where('poi_media_id', $poi_media->id)->get();
                    if (count($temp_qrroom_links) > 0) { //if not empty goes here-ara iparxi etsi push sto DONT INCLUDE
                        array_push($poimedia_DONT_include, $poi_media);
                    } else { //if  empty goes here -ara den iparxi etsi to kanume push kai to dixnume
                        array_push($poimedia_merged, $poi_media);
                    }
                }
            }
            foreach ($storytellings as $storytelling) {
                foreach ($temp_qrrooms as $temp_qrroom) {
                    $temp_qrroom_links = Qr_room_link::where('qr_room_id', $temp_qrroom->id)->where('storytelling_id', $storytelling->id)->get();
                    if (count($temp_qrroom_links) > 0) { //if not empty goes here-ara iparxi etsi push sto DONT INCLUDE
                        array_push($storytelling_DONT_include, $storytelling);
                    } else { //if  empty goes here -ara den iparxi etsi to kanume push kai to dixnume
                        array_push($storytelling_merged, $storytelling);
                    }
                }
            }

            //array_push($poimedia_merged, $poimedia);
            \array_splice($tempTable, 0, 1); //removes 1st record from array
            $loopIter = count($poichildren);
            for ($x = 0; $x < $loopIter; $x++) {
                array_push($tempTable, $poichildren[$x]->id);
            }
            if (($loopIter == 0) && (count($tempTable) == 0)) {
                $isZero = true;
            }
        }

        //removes from array the ones that have already added to qr room links
        foreach ($poimedia_DONT_include as $pm_DONT_include) {
            $poimedia_merged = array_diff($poimedia_merged, [$pm_DONT_include]);
        }
        foreach ($storytelling_DONT_include as $story_DONT_include) {
            $storytelling_merged = array_diff($storytelling_merged, [$story_DONT_include]);
        }

        $poimedia_merged = array_unique($poimedia_merged);
        $storytelling_merged = array_unique($storytelling_merged);
        return view('qrrooms_linked.create')->with('qr_roomid', $qrroom_group->qr_room_id)->with('poimedia_merged', $poimedia_merged)->with('qrroom_group_id', $qrroom_group_id)->with('storytelling_merged', $storytelling_merged);
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
            'name' => 'required'
        ]);
        $poi_media = Validator::make($request->all(), [
            'poi_media_id' => 'required',
        ]);
        $storytelling = Validator::make($request->all(), [
            'storytelling_id' => 'required',
        ]);
        if (!$storytelling->fails() && !$poi_media->fails()) {
            return redirect('create-qrrooms-linked/' . $request->input('qr_group_id'))
                ->withErrors("You cant choose both poi media and storytelling choose only one of them")
                ->withInput();
        } else if ($storytelling->fails() && $poi_media->fails()) {
            return redirect('create-qrrooms-linked/' . $request->input('qr_group_id'))
                ->withErrors("You need to choose one poi media or one storytelling not both")
                ->withInput();
        }
        $qrroom_linked = new Qr_room_link;
        $qrroom_linked->name = $request->input('name');

        $qrroom_linked->x_position = $request->input('x_position');
        $qrroom_linked->y_position = $request->input('y_position');
        $qrroom_linked->z_position = $request->input('z_position');

        $qrroom_linked->x_scale = $request->input('x_scale');
        $qrroom_linked->y_scale = $request->input('y_scale');
        $qrroom_linked->z_scale = $request->input('z_scale');

        $qrroom_linked->x_rotation = $request->input('x_rotation');
        $qrroom_linked->y_rotation = $request->input('y_rotation');
        $qrroom_linked->z_rotation = $request->input('z_rotation');

        $qrroom_linked->qr_room_id = $request->input('qr_roomid');

        $qrroom_linked->poi_media_id = $request->input('poi_media_id');
        $qrroom_linked->group_id = $request->input('qr_group_id');
        $qrroom_linked->storytelling_id = $request->input('storytelling_id');
        $qrroom_linked->save();



        $qr_room = Qr_room::find($qrroom_linked->qr_room_id);
        $qr_room_grouped = Qr_room_group::find($qrroom_linked->group_id);
        $qr_room_links = Qr_room_link::where('group_id', $qrroom_linked->group_id)->get();

        return view('qrrooms_groups.show')->with('qr_room', $qr_room)->with('qr_room_grouped', $qr_room_grouped)->with('qr_room_links', $qr_room_links);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($qrroom_link_id)
    {
        //$qr_room_grouped=Qr_room_group::find($qrroom_group_id);
        $qr_room_link = Qr_room_link::find($qrroom_link_id);
        $qr_room_grouped = Qr_room_group::find($qr_room_link->group_id);
        //$qr_room_link =Qr_room_link::where('group_id', $qr_room_grouped->id)->get();
        $qr_room = Qr_room::where('id', $qr_room_link->qr_room_id)->get();
        $poimedia = Poimedia::find($qr_room_link->poi_media_id);
        $storytelling = Storytelling::find($qr_room_link->storytelling_id);
        return view('qrrooms_linked.show')->with('qr_room_link', $qr_room_link)->with('qr_room', $qr_room)->with('poimedia', $poimedia)->with('qr_room_grouped', $qr_room_grouped)->with('storytelling', $storytelling);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($qrroom_linked_id)
    {
        $qr_room_link = Qr_room_link::find($qrroom_linked_id);
        $qrroom = Qr_room::find($qr_room_link->qr_room_id);
        $poi = Poi::find($qrroom->poi_id);
        $tempTable = [$poi->id];
        $isZero = false;
        $poimedia_merged = [];
        $poimedia_DONT_include = [];
        $storytelling_merged = [];
        $storytelling_DONT_include = [];
        while ($isZero == false) {
            $poichildren = Poi::where('parent_poi', $tempTable[0])->get();
            $poimedia = Poimedia::where('poi_id', $tempTable[0])->get();
            $storytellings = Storytelling::where('poi_id', $tempTable[0])->get();
            $temp_qrrooms = Qr_room::where('poi_id', $tempTable[0])->get();
            foreach ($poimedia as $poi_media) {
                foreach ($temp_qrrooms as $temp_qrroom) {
                    $temp_qrroom_links = Qr_room_link::where('qr_room_id', $temp_qrroom->id)->where('poi_media_id', $poi_media->id)->get();
                    if (count($temp_qrroom_links) > 0) { //if not empty goes here-ara iparxi etsi push sto DONT INCLUDE
                        array_push($poimedia_DONT_include, $poi_media);
                    } else { //if  empty goes here -ara den iparxi etsi to kanume push kai to dixnume
                        array_push($poimedia_merged, $poi_media);
                    }
                }
            }
            foreach ($storytellings as $storytelling) {
                foreach ($temp_qrrooms as $temp_qrroom) {
                    $temp_qrroom_links = Qr_room_link::where('qr_room_id', $temp_qrroom->id)->where('storytelling_id', $storytelling->id)->get();
                    if (count($temp_qrroom_links) > 0) { //if not empty goes here-ara iparxi etsi push sto DONT INCLUDE
                        array_push($storytelling_DONT_include, $storytelling);
                    } else { //if  empty goes here -ara den iparxi etsi to kanume push kai to dixnume
                        array_push($storytelling_merged, $storytelling);
                    }
                }
            }

            //array_push($poimedia_merged, $poimedia);
            \array_splice($tempTable, 0, 1); //removes 1st record from array
            $loopIter = count($poichildren);
            for ($x = 0; $x < $loopIter; $x++) {
                array_push($tempTable, $poichildren[$x]->id);
            }
            if (($loopIter == 0) && (count($tempTable) == 0)) {
                $isZero = true;
            }
        }

        //removes from array the ones that have already added to qr room links
        foreach ($poimedia_DONT_include as $pm_DONT_include) {
            $poimedia_merged = array_diff($poimedia_merged, [$pm_DONT_include]);
        }
        foreach ($storytelling_DONT_include as $story_DONT_include) {
            $storytelling_merged = array_diff($storytelling_merged, [$story_DONT_include]);
        }

        //gets current selections for edit window preselect options
        $current_poi_media_selected = Poimedia::where('id', $qr_room_link->poi_media_id)->get();
        if (count($current_poi_media_selected) > 0) {
            array_push($poimedia_merged, $current_poi_media_selected[0]);

            $current_selection_media_OR_story="poimedia";
            $current_selection=$current_poi_media_selected;
        }

        $current_storytelling_selected = Storytelling::where('id', $qr_room_link->storytelling_id)->get();
        if (count($current_storytelling_selected) > 0) {
            array_push($storytelling_merged,  $current_storytelling_selected[0]);

            $current_selection_media_OR_story="storytelling";
            $current_selection=$current_storytelling_selected;
        }

        $poimedia_merged = array_unique($poimedia_merged);
        $storytelling_merged = array_unique($storytelling_merged);

        $qr_room = Qr_room::where('id', $qr_room_link->qr_room_id)->get();
        $poimedia = Poimedia::find($qr_room_link->poi_media_id);
        $qr_room_grouped = Qr_room_group::find($qr_room_link->group_id);

        return view('qrrooms_linked.edit')->with('qr_room_link', $qr_room_link)->with('qr_room', $qr_room)->with('poimedia_merged', $poimedia_merged)->with('poimedia', $poimedia)->with('qr_room_grouped', $qr_room_grouped)->with('storytelling_merged', $storytelling_merged)->with('current_selection_media_OR_story',$current_selection_media_OR_story)->with('current_selection',$current_selection);
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
            'name' => 'required'
        ]);
        $poi_media = Validator::make($request->all(), [
            'poi_media_id' => 'required',
        ]);
        $storytelling = Validator::make($request->all(), [
            'storytelling_id' => 'required',
        ]);
        if (!$storytelling->fails() && !$poi_media->fails()) {
            return redirect('qrrooms_linked/' . $id . '/edit')
                ->withErrors("You cant choose both poi media and storytelling choose only one of them")
                ->withInput();
        } else if ($storytelling->fails() && $poi_media->fails()) {
            return redirect('qrrooms_linked/' . $id . '/edit')
                ->withErrors("You need to choose one poi media or one storytelling not both")
                ->withInput();
        }

        $qrroom_linked = Qr_room_link::find($id);

        $qrroom_linked->name = $request->input('name');

        $qrroom_linked->x_position = $request->input('x_position');
        $qrroom_linked->y_position = $request->input('y_position');
        $qrroom_linked->z_position = $request->input('z_position');

        $qrroom_linked->x_scale = $request->input('x_scale');
        $qrroom_linked->y_scale = $request->input('y_scale');
        $qrroom_linked->z_scale = $request->input('z_scale');

        $qrroom_linked->x_rotation = $request->input('x_rotation');
        $qrroom_linked->y_rotation = $request->input('y_rotation');
        $qrroom_linked->z_rotation = $request->input('z_rotation');

        #$qrroom_linked->qr_room_id=$request->input('qr_roomid');

        $qrroom_linked->poi_media_id = $request->input('poi_media_id');
        $qrroom_linked->group_id = $request->input('qr_group_id');
        $qrroom_linked->storytelling_id = $request->input('storytelling_id');
        $qrroom_linked->save();

        $qr_room = Qr_room::find($qrroom_linked->qr_room_id);
        $qr_room_grouped = Qr_room_group::find($qrroom_linked->group_id);
        $qr_room_links = Qr_room_link::where('group_id', $qrroom_linked->group_id)->get();

        return view('qrrooms_groups.show')->with('qr_room', $qr_room)->with('qr_room_grouped', $qr_room_grouped)->with('qr_room_links', $qr_room_links);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($qr_room_linked_id)
    {
        $qr_room_link = Qr_room_link::find($qr_room_linked_id);

        $qr_room = Qr_room::find($qr_room_link->qr_room_id);
        $qr_room_grouped = Qr_room_group::find($qr_room_link->group_id);
        $qr_room_link->delete();

        $qr_room_links = Qr_room_link::where('group_id', $qr_room_link->group_id)->get();

        return view('qrrooms_groups.show')->with('qr_room', $qr_room)->with('qr_room_grouped', $qr_room_grouped)->with('qr_room_links', $qr_room_links);
    }
}
