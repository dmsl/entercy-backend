@extends('layouts.app')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<a href="{{ URL::to('/') }}/qrrooms_groups/{{$qr_room_grouped->id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Edit QR room Link </u></h1>
<br>
  {!! Form::open(['action' => ['Qr_room_linksController@update', $qr_room_link->id,$qr_room_grouped->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', $qr_room_link->name, ['class' => 'form-control', 'placeholder' => 'Name'] )}}
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
        {{Form::label('x_position', 'X position')}}
        {{Form::number('x_position', $qr_room_link->x_position, ['class' => 'form-control','step' => 'any','placeholder' => 'Enter Number Value'])}}
      </div>
      <div class="form-group">
        {{Form::label('y_position', 'Y position')}}
        {{Form::number('y_position', $qr_room_link->y_position, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_position', 'Z position')}}
        {{Form::number('z_position', $qr_room_link->z_position, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>

      <div class="form-group">
        {{Form::label('x_scale', 'X scale')}}
        {{Form::number('x_scale', $qr_room_link->x_scale, ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_scale', 'Y scale')}}
        {{Form::number('y_scale', $qr_room_link->y_scale, ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_scale', 'Z scale')}}
        {{Form::number('z_scale', $qr_room_link->z_scale, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>

      <div class="form-group">
        {{Form::label('x_rotation', 'X rotation')}}
        {{Form::number('x_rotation', $qr_room_link->x_rotation, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_rotation', 'Y rotation')}}
        {{Form::number('y_rotation', $qr_room_link->y_rotation, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_rotation', 'Z rotation')}}
        {{Form::number('z_rotation', $qr_room_link->z_rotation, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>
    <div class="form-group" hidden>
      {{Form::label('qr_roomid', 'qr_roomid')}}
      {{Form::text('qr_roomid', $qr_room_link->qr_room_id , ['class' => 'form-control', 'placeholder' => '' ] )}}
    </div>
    <div class="form-group" hidden>
        {{Form::label('qr_group_id', 'qr_group_id')}}
        {{Form::text('qr_group_id', $qr_room_grouped->id , ['class' => 'form-control', 'placeholder' => '' ] )}}
    </div>

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
