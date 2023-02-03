@extends('layouts.app')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<a href="{{ URL::to('/') }}/outdoor_groups/{{$outdoor_group->id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Edit Outdoor Links</u></h1>
<br>
  {!! Form::open(['action' => ['Outdoor_linksController@update', $outdoor_links->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}


  <div class="form-group" hidden>
    {{Form::label('outdoor_group_id', 'outdoor_group_id')}}
    {{Form::text('outdoor_group_id', $outdoor_group->id , ['class' => 'form-control', 'placeholder' => '' ] )}}
  </div>
    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', $outdoor_links->name, ['class' => 'form-control', 'placeholder' => 'Name'] )}}
    </div>
    <div class="form-group">
        {{Form::label('poi_media_id', 'Please select Poi Media')}}
        <br>
        <select id="poi_media_id" class="browser-default custom-select form-control" name="poi_media_id" required >
        <option selected disabled >Please select Poi Media</option>
        @foreach($poimedia_merged as $poi_media)
            <option value="{{$poi_media->id}}">{{$poi_media->name}}</option>
        @endforeach
        </select>
    </div>
    <div class="form-group">
        {{Form::label('storytelling_id', 'Please select Storytelling')}}
        <br>
        <select id="storytelling_id" class="browser-default custom-select form-control" name="storytelling_id" required >
        <option selected disabled>Please select Storytelling</option>
        @foreach($storytelling_merged as $story)
            <option value="{{$story->id}}">{{$story->name}}</option>
        @endforeach
        </select>
    </div>
    <div class="form-group">
        {{Form::label('latitude', 'Latitude')}}
        {{Form::number('latitude', $outdoor_links->latitude, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter latitude Value'] )}}
    </div>
    <div class="form-group">
        {{Form::label('longitude', 'Longitude')}}
        {{Form::number('longitude', $outdoor_links->longitude, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter longitude Value'] )}}
    </div>
    <div class="form-group">
        {{Form::label('x_scale', 'X scale')}}
        {{Form::number('x_scale', $outdoor_links->x_scale, ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_scale', 'Y scale')}}
        {{Form::number('y_scale', $outdoor_links->y_scale, ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_scale', 'Z scale')}}
        {{Form::number('z_scale', $outdoor_links->z_scale, ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>

      <div class="form-group">
        {{Form::label('x_rotation', 'X rotation')}}
        {{Form::number('x_rotation', $outdoor_links->x_rotation, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_rotation', 'Y rotation')}}
        {{Form::number('y_rotation', $outdoor_links->y_rotation, ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_rotation', 'Z rotation')}}
        {{Form::number('z_rotation', $outdoor_links->z_rotation, ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
    <br>

    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}


  <script>
    $( document ).ready(function() {

     if ('{{$current_selection_media_OR_story}}'=="storytelling")
     {
      document.getElementById('storytelling_id').value='{{$current_selection[0]->id}}';
     }
     else
     {
      document.getElementById('poi_media_id').value='{{$current_selection[0]->id}}';
     }

   });
</script>
@endsection
