@extends('layouts.app')

@section('content')

  <h1><u>Create QR Room Link</u></h1>
<br>
  {!! Form::open(['action' => 'Qr_room_linksController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}


    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'] )}}
    </div>

    <div class="form-group">
        {{Form::label('poi media', 'Poi media')}}
        <br>
        <select id="poi_media_id" class="browser-default custom-select form-control" name="poi_media_id" required >
          <option selected disabled >Please select Poi Media</option>
          @foreach($poimedia_merged as $pm)
               <option value="{{$pm->id}}">{{$pm->name}}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        {{Form::label('storytellings', 'storytelling')}}
        <br>
        <select id="storytelling_id" class="browser-default custom-select form-control" name="storytelling_id" required >
          <option selected disabled >Please select Storytelling</option>
          @foreach($storytelling_merged as $story)
               <option value="{{$story->id}}">{{$story->name}}</option>
          @endforeach
        </select>
      </div>
    <div class="form-group">
        {{Form::label('x_position', 'X position')}}
        {{Form::number('x_position', '', ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_position', 'Y position')}}
        {{Form::number('y_position', '', ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_position', 'Z position')}}
        {{Form::number('z_position', '', ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>

      <div class="form-group">
        {{Form::label('x_scale', 'X scale')}}
        {{Form::number('x_scale', '', ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_scale', 'Y scale')}}
        {{Form::number('y_scale', '', ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_scale', 'Z scale')}}
        {{Form::number('z_scale', '', ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>

      <div class="form-group">
        {{Form::label('x_rotation', 'X rotation')}}
        {{Form::number('x_rotation', '', ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_rotation', 'Y rotation')}}
        {{Form::number('y_rotation', '', ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_rotation', 'Z rotation')}}
        {{Form::number('z_rotation', '', ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
    <div class="form-group" hidden>
      {{Form::label('qr_roomid', 'qr_roomid')}}
      {{Form::text('qr_roomid', $qr_roomid , ['class' => 'form-control', 'placeholder' => '' ] )}}
    </div>
    <div class="form-group" hidden>
        {{Form::label('qr_group_id', 'qr_group_id')}}
        {{Form::text('qr_group_id', $qrroom_group_id , ['class' => 'form-control', 'placeholder' => '' ] )}}
    </div>

    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}



@endsection
