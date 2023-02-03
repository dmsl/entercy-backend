@extends('layouts.app')

@section('content')

@if ($poi->site_id != 0)
    <a href="{{ URL::to('/') }}/sites/{{$poi->site_id}}" class="btn btn-primary">Back to Site</a>
@else
    <a href="{{ URL::to('/') }}/pois/{{$poi->parent_poi}}" class="btn btn-primary">Back to Parent POI</a>
@endif

<br><br>
  <h1><u>{{$poi->name}}</u>  </h1>

  <!--- <img style="width:100%" src="/storage/cover_images/{{$poi->cover_image}}"> --->
  <br>
  <!---<div class="form-group">
     <label>Description:</label>
     <input class="form-control" disabled value="{{$poi->description}}">
  </div>--->

  <div class="form-group ">
      {{Form::label('name', 'Name in English')}}
      {{Form::text('name', $poi->name , ['class' => 'form-control ', 'disabled','placeholder' => ''] )}}
    </div>
     <div class="form-group">
      {{Form::label('name_gr', 'Name in Greek')}}
      {{Form::text('name_gr',$poi->name_gr , ['class' => 'form-control ', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ru', 'Name in Russian')}}
      {{Form::text('name_ru',$poi->name_ru, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_it', 'Name in Italian')}}
      {{Form::text('name_it', $poi->name_it, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_fr', 'Name in French')}}
      {{Form::text('name_fr', $poi->name_fr, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Name in German')}}
      {{Form::text('name_ge', $poi->name_ge, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>

    <div class="form-group">
     <label>Description in English:</label>
     <textarea class="form-control" rows="3" disabled> {{$poi->description}} </textarea >
    </div>
    <div class="form-group">
     <label>Description in Greek:</label>
     <textarea class="form-control" rows="3" disabled> {{$poi->description_gr}} </textarea >
    </div>
    <div class="form-group">
     <label>Description in Russian:</label>
     <textarea class="form-control" rows="3" disabled> {{$poi->description_ru}} </textarea >
    </div>
    <div class="form-group">
     <label>Description in Italian:</label>
     <textarea class="form-control" rows="3" disabled> {{$poi->description_it}} </textarea >
    </div>
    <div class="form-group">
     <label>Description in French:</label>
     <textarea class="form-control" rows="3" disabled> {{$poi->description_fr}} </textarea >
    </div>
    <div class="form-group">
     <label>Description in German:</label>
     <textarea class="form-control" rows="3" disabled> {{$poi->description_ge}} </textarea >
    </div>


    @if($poi->site_id != '0')
      <div class="form-group">
        <label>Outdoor or Indoor:</label>
        <input class="form-control" disabled value="{{$poi->outdoor_indoor}}">
     </div>
     @if ($poi->indoor_type != '')
       <div class="form-group">
        <label>IndoorType:</label>
        <input class="form-control" disabled value="{{$poi->indoor_type}}">
       </div>
     @endif
     @endif

  <div class="form-group">
    <br>
    <label>Chronological:</label>
    <select id="typeOption" class="browser-default custom-select form-control" name="chronological" disabled >
      @if ($chronological != '')
      <option selected disabled >{{$chronological->name}}</option>
      @endif
    </select>
  </div>
  <div class="form-group">
    <br>
    <label>Sub-Chronological:</label>
    <select id="typeOption2" class="browser-default custom-select form-control" name="sub_chronological" disabled >
       @if ($sub_chronological != '')
      <option selected disabled >{{$sub_chronological->name}}</option>
      @endif
    </select>
  </div>
  <div class="form-group">
     <label>Year:</label>
     <input class="form-control" disabled value="{{$poi->year}}">
  </div>
  <div class="form-group">
     <label>Coordinates:</label>
     <input class="form-control" disabled value="{{$poi->coordinates}}">
  </div>
  <div class="form-group">
     <label>Toponym in English:</label>
     <input class="form-control" disabled value="{{$poi->toponym}}">
  </div>
  <div class="form-group">
     <label>Toponym in Greek:</label>
     <input class="form-control" disabled value="{{$poi->toponym_gr}}">
  </div>
  <div class="form-group">
     <label>Toponym in Russian:</label>
     <input class="form-control" disabled value="{{$poi->toponym_ru}}">
  </div>
  <div class="form-group">
     <label>Toponym in Italian:</label>
     <input class="form-control" disabled value="{{$poi->toponym_it}}">
  </div>
  <div class="form-group">
     <label>Toponym in French:</label>
     <input class="form-control" disabled value="{{$poi->toponym_fr}}">
  </div>
  <div class="form-group">
     <label>Toponym in German:</label>
     <input class="form-control" disabled value="{{$poi->toponym_ge}}">
  </div>

  <!---
  @if(count($poiartifact_foundon)>0)
   <div class="form-group">
      <label>Artifact found on:</label>
      <input class="form-control" disabled value="{{$poiartifact_foundon[0]->name}}">
   </div>
  @endif
--->

  <div class="form-group">
        {{Form::label('publicly_available', 'Publicly available')}}
        <br>
        <select id="publicly_available" class="browser-default custom-select form-control" name="publicly_available" disabled>
         <option selected disabled>{{$poi->publicly_available}}</option>
         <option value="Yes">Yes</option>
         <option value="No">No</option>
       </select>
     </div>

  <hr>
  <small>Created on {{$poi->created_at}}  </small>
  <hr>

  <a href="{{ URL::to('/') }}/pois/{{$poi->id}}/edit" class="btn btn-warning">Edit</a>

      <div class="float-right">
        {!!Form::open(['action' => ['PoisController@destroy', $poi->id], 'method' => 'POST', 'class' => 'pull-right', 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>




      <br><br>
      <hr>
          <div><h1><u>POI Media</u></h1> </div>

          <div class="alert alert-primary" role="alert">
            <!---It is recommended to have a "POI Cover Image" and a "Description-Text in English" for every POI.--->
            It is recommended to have a "POI Cover Image" for every POI.
          </div>

          <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">Manage POI Media</div>
                        <div class="card-body">
                            <div class="panel-body">
                              <div>
                                <div class="float-md-left"><h1>POI Media</h1> </div>
                                <div class="float-md-right"><a href="{{ URL::to('/') }}/create-poimedia/{{$poi->id}}" class="btn btn-primary">Create POI Media</a> </div>
                              </div>

                              @if(count($poimedia)>0)
                                <table class="table table-striped">
                                    <tr>
                                      <th>Name</th>
                                      <th></th>
                                      <th></th>
                                    </tr>
                                    @foreach($poimedia as $poimedia)
                                    <tr>
                                      <td>
                                        <h5><a href="{{ URL::to('/') }}/poimedia/{{$poimedia->id}}">{{$poimedia->name}}</a></h5>
                                      </td>
                                      <td><a href="{{ URL::to('/') }}/poimedia/{{$poimedia->id}}/edit" class="btn btn-warning">Edit</a></td>
                                      <td>
                                        {!!Form::open(['action' => ['PoimediasController@destroy', $poimedia->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                          {{Form::hidden('_method', 'DELETE')}}
                                          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                        {!!Form::close()!!}
                                      </td>
                                    </tr>
                                    @endforeach
                                </table>
                              @else
                                <br><br><br>
                                <p>There are no registered Media.</p>
                              @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<!---
        <br>
        <hr>

        <div><h1><u>POI Artifacts</u></h1> </div>

      <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Manage POI Artifacts</div>
                    <div class="card-body">
                        <div class="panel-body">
                          <div>
                            <div class="float-md-left"><h1>POI Artifacts</h1> </div>
                            <div class="float-md-right"><a href="{{ URL::to('/') }}/create-poiartifacts/{{$poi->id}}" class="btn btn-primary">Add POI Artifact</a> </div>
                          </div>

                          @if(count($poiartifact)>0)
                            <table class="table table-striped">
                                <tr>
                                  <th>Artifact Name</th>
                                  <th></th>
                                  <th></th>
                                </tr>
                                @foreach($poiartifact as $poiartifact)
                                <tr>
                                  <td>
                                    <h5 style="color: #3490dc;">{{$poiartifact->name}}</h5>
                                  </td>
                                  <td></td>
                                  <td>
                                    <a href="{{ URL::to('/') }}/removeartifact/{{$poiartifact->id}}" class="btn btn-danger">Remove</a>                                      
                                  </td>
                                </tr>
                                @endforeach
                            </table>
                          @else
                            <br><br><br>
                            <p>There are no registered POI Artifacts.</p>
                          @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

--->

    <br>
        <hr>

        <div><h1><u>POI Tags</u></h1> </div>

      <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Manage POI Tags</div>
                    <div class="card-body">
                        <div class="panel-body">
                          <div>
                            <div class="float-md-left"><h1>POI Tags</h1> </div>
                            <div class="float-md-right"><a href="{{ URL::to('/') }}/create-poitags/{{$poi->id}}" class="btn btn-primary">Create POI Tag</a> </div>
                          </div>

                          @if(count($poitag)>0)
                            <table class="table table-striped">
                                <tr>
                                  <th>Tag Name</th>
                                  <th></th>
                                  <th></th>
                                </tr>
                                @foreach($poitag as $poitag)
                                <tr>
                                  <td>
                                    <h5 style="color: #3490dc;">{{$poitag->name}}</h5>
                                  </td>
                                  <td></td>
                                  <td>
                                     {!!Form::open(['action' => ['PoitagsController@destroy', $poitag->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                      {{Form::hidden('_method', 'DELETE')}}
                                      {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                    {!!Form::close()!!}
                                  </td>
                                </tr>
                                @endforeach
                            </table>
                          @else
                            <br><br><br>
                            <p>There are no registered POI Tags.</p>
                          @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <br>
      <hr>
          <div><h1><u>POI Story Telling</u></h1> </div>

          <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">Manage POI Story Telling</div>
                        <div class="card-body">
                            <div class="panel-body">
                              <div>
                                <div class="float-md-left"><h1>POI Story Telling</h1> </div>
                                <div class="float-md-right"><a href="{{ URL::to('/') }}/create-storytelling/{{$poi->id}}" class="btn btn-primary">Create POI Story Telling</a> </div>
                              </div>

                              @if(count($storytelling)>0)
                                <table class="table table-striped">
                                    <tr>
                                      <th>Name</th>
                                      <th></th>
                                      <th></th>
                                    </tr>
                                    @foreach($storytelling as $storytelling)
                                    <tr>
                                      <td>
                                        <h5><a href="{{ URL::to('/') }}/storytellings/{{$storytelling->id}}">{{$storytelling->name}} [{{$storytelling->language}}]</a></h5>
                                      </td>
                                      <td><a href="{{ URL::to('/') }}/storytellings/{{$storytelling->id}}/edit" class="btn btn-warning">Edit</a></td>
                                      <td>
                                        {!!Form::open(['action' => ['StorytellingsController@destroy', $storytelling->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                          {{Form::hidden('_method', 'DELETE')}}
                                          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                        {!!Form::close()!!}
                                      </td>
                                    </tr>
                                    @endforeach
                                </table>
                              @else
                                <br><br><br>
                                <p>There are no registered POI Story tellings.</p>
                              @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



      <br>
      <hr>



    <div><h1><u>Children POIs</u></h1> </div>

        <div class="container">
          <div class="row justify-content-center">
              <div class="col-md-10">
                  <div class="card">
                      <div class="card-header">Manage Children POIs</div>
                      <div class="card-body">
                          <div class="panel-body">
                            <div>
                              <div class="float-md-left"><h1>Children POIs</h1> </div>
                              <div class="float-md-right"><a href="{{ URL::to('/') }}/create-sub-poi/{{$poi->id}}" class="btn btn-primary">Create Sub-POI</a> </div>
                            </div>

                            @if(count($poichildren)>0)
                              <table class="table table-striped">
                                  <tr>
                                    <th>Name</th>
                                    <th></th>
                                    <th></th>
                                  </tr>
                                  @foreach($poichildren as $poichild)
                                  <tr>
                                    <td>
                                      <h5><a href="{{ URL::to('/') }}/pois/{{$poichild->id}}">{{$poichild->name}}</a></h5>
                                      <!---<br>
                                      <small>Created on {{$poichild->created_at}}</small>--->
                                    </td>
                                    <td><a href="{{ URL::to('/') }}/pois/{{$poichild->id}}/edit" class="btn btn-warning">Edit</a></td>
                                    <td>
                                       {!!Form::open(['action' => ['PoisController@destroy', $poichild->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                        {{Form::hidden('_method', 'DELETE')}}
                                        {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                      {!!Form::close()!!}
                                    </td>
                                  </tr>
                                  @endforeach
                              </table>
                            @else
                              <br><br><br>
                              <p>There are no registered POIs.</p>
                            @endif

                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>


       <br>
      <hr>


    @if($poi->site_id != '0' && $poi->indoor_type == 'QR codes')
      <div><h1><u>QR Rooms</u></h1> </div>

          <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">Manage QR Rooms</div>
                        <div class="card-body">
                            <div class="panel-body">
                              <div>
                                <div class="float-md-left"><h1>QR Rooms</h1> </div>
                                <div class="float-md-right"><a href="{{ URL::to('/') }}/create-qrrooms/{{$poi->id}}" class="btn btn-primary">Create QR Room</a> </div>
                              </div>

                              @if(count($qrrooms)>0)
                                <table class="table table-striped">
                                    <tr>
                                      <th>Name</th>
                                      <th></th>
                                      <th></th>
                                    </tr>
                                    @foreach($qrrooms as $qrroom)
                                    <tr>
                                      <td>
                                        <h5><a href="{{ URL::to('/') }}/qrrooms/{{$qrroom->id}}">{{$qrroom->name}}</a></h5>
                                      </td>
                                      <td><a href="{{ URL::to('/') }}/qrrooms/{{$qrroom->id}}/edit" class="btn btn-warning">Edit</a></td>
                                      <td>
                                         {!!Form::open(['action' => ['Qr_roomsController@destroy', $qrroom->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                          {{Form::hidden('_method', 'DELETE')}}
                                          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                        {!!Form::close()!!}
                                      </td>
                                    </tr>
                                    @endforeach
                                </table>
                              @else
                                <br><br><br>
                                <p>There are no registered QR Rooms.</p>
                              @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      @endif

      <br>
      


    @if($poi->site_id != '0' && $poi->indoor_type == 'Beacons')
      <hr>
      <div><h1><u>Beacons</u></h1> </div>

          <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">Manage Beacons</div>
                        <div class="card-body">
                            <div class="panel-body">
                              <div>
                                <div class="float-md-left"><h1>Beacons</h1> </div>
                                <div class="float-md-right"><a href="{{ URL::to('/') }}/create-beacons/{{$poi->id}}" class="btn btn-primary">Create Beacons</a> </div>
                              </div>

                              @if(count($beacons)>0)
                                <table class="table table-striped">
                                    <tr>
                                      <th>Name</th>
                                      <th></th>
                                      <th></th>
                                    </tr>
                                    @foreach($beacons as $beacon)
                                    <tr>
                                      <td>
                                        <h5><a href="{{ URL::to('/') }}/beacons/{{$beacon->id}}">{{$beacon->name}}</a></h5>
                                      </td>
                                      <td><a href="{{ URL::to('/') }}/beacons/{{$beacon->id}}/edit" class="btn btn-warning">Edit</a></td>
                                      <td>
                                         {!!Form::open(['action' => ['BeaconsController@destroy', $beacon->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                          {{Form::hidden('_method', 'DELETE')}}
                                          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                        {!!Form::close()!!}
                                      </td>
                                    </tr>
                                    @endforeach
                                </table>
                              @else
                                <br><br><br>
                                <p>There are no registered Beacons.</p>
                              @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      @endif

<br><br>
    @if ($poi->site_id != '0' && $poi->outdoor_indoor == 'Outdoor')
    <div><h1><u>Outdoor Groups</u></h1> </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Manage Outdoor Groups</div>
                    <div class="card-body">
                        <div class="panel-body">
                          <div>
                            <div class="float-md-left"><h1>Outdoor Groups</h1> </div>
                            <div class="float-md-right"><a href="{{ URL::to('/') }}/create-outdoor-groups/{{$poi->id}}" class="btn btn-primary">Create Outdoor Groups</a> </div>
                          </div>

                          @if(count($outdoor_groups)>0)
                            <table class="table table-striped">
                                <tr>
                                  <th>Name</th>
                                  <th></th>
                                  <th></th>
                                </tr>
                                @foreach($outdoor_groups as $outdoor_group)
                                <tr>
                                  <td>
                                    <h5><a href="{{ URL::to('/') }}/outdoor_groups/{{$outdoor_group->id}}">{{$outdoor_group->name}}</a></h5>
                                  </td>
                                  <td><a href="{{ URL::to('/') }}/outdoor_groups/{{$outdoor_group->id}}/edit" class="btn btn-warning">Edit</a></td>
                                  <td>
                                     {!!Form::open(['action' => ['Outdoor_groupsController@destroy', $outdoor_group->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                      {{Form::hidden('_method', 'DELETE')}}
                                      {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                    {!!Form::close()!!}
                                  </td>
                                </tr>
                                @endforeach
                            </table>
                          @else
                            <br><br><br>
                            <p>There are no registered Outdoor Groups.</p>
                          @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <br><br>

@endsection
