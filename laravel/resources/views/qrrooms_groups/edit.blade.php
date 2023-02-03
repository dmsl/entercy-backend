@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/qrrooms_groups/{{$qr_room_grouped->id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Edit Qr room Groups</u></h1>
<br>
  {!! Form::open(['action' => ['Qr_room_groupsController@update', $qr_room_grouped->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}


    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', $qr_room_grouped->name, ['class' => 'form-control', 'placeholder' => 'Name'] )}}
    </div>
    <div class="form-group">
        {{Form::label('x_position', 'X position')}}
        {{Form::number('x_position', $qr_room_grouped->x_position, ['class' => 'form-control','step' => 'any','placeholder' => 'Enter Number Value'])}}
      </div>
      <div class="form-group">
        {{Form::label('y_position', 'Y position')}}
        {{Form::number('y_position', $qr_room_grouped->y_position, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_position', 'Z position')}}
        {{Form::number('z_position', $qr_room_grouped->z_position, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>

      <div class="form-group">
        {{Form::label('x_scale', 'X scale')}}
        {{Form::number('x_scale', $qr_room_grouped->x_scale, ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_scale', 'Y scale')}}
        {{Form::number('y_scale', $qr_room_grouped->y_scale, ['class' => 'form-control', 'step' => 'any','placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_scale', 'Z scale')}}
        {{Form::number('z_scale', $qr_room_grouped->z_scale, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>

      <div class="form-group">
        {{Form::label('x_rotation', 'X rotation')}}
        {{Form::number('x_rotation', $qr_room_grouped->x_rotation, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('y_rotation', 'Y rotation')}}
        {{Form::number('y_rotation', $qr_room_grouped->y_rotation, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>
      <div class="form-group">
        {{Form::label('z_rotation', 'Z rotation')}}
        {{Form::number('z_rotation', $qr_room_grouped->z_rotation, ['class' => 'form-control','step' => 'any', 'placeholder' => 'Enter Number Value'] )}}
      </div>
    <div class="form-group" hidden>
        {{Form::label('qr_room_id', 'qr_room_id')}}
        {{Form::text('qr_room_id', $qr_room_grouped->qr_room_id , ['class' => 'form-control', 'placeholder' => '' ] )}}
      </div>
    <br>

    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}



@endsection
