@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/outdoor_groups/{{$outdoor_group->id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Show Outdoor Links</u></h1>
<br>


  <div class="form-group" hidden>
    {{Form::label('outdoor_group_id', 'outdoor_group_id')}}
    {{Form::text('outdoor_group_id', $outdoor_group->id , ['class' => 'form-control', 'placeholder' => '' ] )}}
  </div>
    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', $outdoor_links->name, ['class' => 'form-control', 'disabled' => 'Name'] )}}
    </div>
    @if(!empty($poimedia))
    <div class="form-group">
        <br>
        <label>Poi Media:</label>
        <select id="poi_media_id" class="browser-default custom-select form-control" name="poi_media_id" disabled >
        <option selected disabled >{{$poimedia->name}}</option>
        </select>
    </div>
    @endif
    @if(!empty($storytelling))
    <div class="form-group">
        <br>
        <label>Storytelling:</label>
        <select id="storytelling_id" class="browser-default custom-select form-control" name="storytelling_id" disabled >
        <option selected disabled >{{$storytelling->name}}</option>
        </select>
    </div>
    @endif
    <div class="form-group">
        {{Form::label('latitude', 'Latitude')}}
        {{Form::number('latitude', $outdoor_links->latitude, ['class' => 'form-control','disabled' => 'Enter latitude Value'] )}}
    </div>
    <div class="form-group">
        {{Form::label('longitude', 'Longitude')}}
        {{Form::number('longitude', $outdoor_links->longitude, ['class' => 'form-control','disabled' => 'Enter longitude Value'] )}}
    </div>
    <div class="form-group">
        {{Form::label('x_scale', 'X scale')}}
        {{Form::number('x_scale', $outdoor_links->x_scale, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_scale', 'Y scale')}}
        {{Form::number('y_scale', $outdoor_links->y_scale, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_scale', 'Z scale')}}
        {{Form::number('z_scale', $outdoor_links->z_scale, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>

      <div class="form-group">
        {{Form::label('x_rotation', 'X rotation')}}
        {{Form::number('x_rotation', $outdoor_links->x_rotation, ['class' => 'form-control','disabled'=> 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_rotation', 'Y rotation')}}
        {{Form::number('y_rotation', $outdoor_links->y_rotation, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_rotation', 'Z rotation')}}
        {{Form::number('z_rotation', $outdoor_links->z_rotation, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
      </div>
    <br>
  <hr>
  <small>Created on {{$outdoor_links->created_at}} </small>
  <hr>

      <a href="{{ URL::to('/') }}/outdoor_links/{{$outdoor_links->id}}/edit" class="btn btn-warning">Edit Oudoor Links</a>

      <div class="float-right">
        {!!Form::open(['action' => ['Outdoor_linksController@destroy', $outdoor_links->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete Outdoor_groups', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>
    <br>
    <br>
  <hr>
@endsection
