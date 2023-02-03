@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/pois/{{$outdoor_groups->poi_id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Show Outdoor Groups</u></h1>
<br>

<div class="form-group">
    {{Form::label('name', 'Name')}}
    {{Form::text('name', $outdoor_groups->name, ['class' => 'form-control', 'disabled' => 'Name'] )}}
  </div>
 <div class="form-group">
        {{Form::label('latitude', 'Latitude')}}
        {{Form::number('latitude', $outdoor_groups->latitude, ['class' => 'form-control','disabled' => 'Enter latitude Value'] )}}
    </div>
    <div class="form-group">
        {{Form::label('longitude', 'Longitude')}}
        {{Form::number('longitude', $outdoor_groups->longitude, ['class' => 'form-control','disabled' => 'Enter longitude Value'] )}}
    </div>
    <div class="form-group">
        {{Form::label('altitude', 'Altitude')}}
        {{Form::number('altitude', $outdoor_groups->altitude, ['class' => 'form-control','disabled' => 'Enter latitude Value'] )}}
    </div>
  <div class="form-group" hidden>
      {{Form::label('poiid', 'poiid')}}
      {{Form::text('poiid', $outdoor_groups->poi_id , ['class' => 'form-control', 'disabled' => '' ] )}}
  </div>
  <br>
  <hr>
  <small>Created on {{$outdoor_groups->created_at}} </small>
  <hr>

      <a href="{{ URL::to('/') }}/outdoor_groups/{{$outdoor_groups->id}}/edit" class="btn btn-warning">Edit Oudoor Groups</a>

      <div class="float-right">
        {!!Form::open(['action' => ['Outdoor_groupsController@destroy', $outdoor_groups->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete Outdoor_groups', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>

      <br>
      <hr>
      
      <div><h1><u>Outdoor Links</u></h1> </div>

          <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">Manage Outdoor Links</div>
                        <div class="card-body">
                            <div class="panel-body">
                              <div>
                                <div class="float-md-left"><h1>Outdoor Links</h1> </div>
                                <div class="float-md-right"><a href="{{ URL::to('/') }}/create-outdoor-links/{{$outdoor_groups->id}}" class="btn btn-primary">Create Outdoor Links</a> </div>
                              </div>

                              @if(count($outdoor_links)>0)
                                <table class="table table-striped">
                                    <tr>
                                      <th>Name</th>
                                      <th></th>
                                      <th></th>
                                    </tr>
                                    @foreach($outdoor_links as $outdoor_link)
                                    <tr>
                                      <td>
                                        <h5><a href="{{ URL::to('/') }}/outdoor_links/{{$outdoor_link->id}}">{{$outdoor_link->name}}</a></h5>
                                      </td>
                                      <td><a href="{{ URL::to('/') }}/outdoor_links/{{$outdoor_link->id}}/edit" class="btn btn-warning">Edit</a></td>
                                      <td>
                                         {!!Form::open(['action' => ['Outdoor_linksController@destroy', $outdoor_link->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                          {{Form::hidden('_method', 'DELETE')}}
                                          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                        {!!Form::close()!!}
                                      </td>
                                    </tr>
                                    @endforeach
                                </table>
                              @else
                                <br><br><br>
                                <p>There are no registered Outdoor Links.</p>
                              @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
