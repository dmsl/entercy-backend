@extends('layouts.app')

@section('content')

  <h1><u>Create QR Room Groups</u></h1>
<br>
  {!! Form::open(['action' => 'Qr_room_groupsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}



    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'] )}}
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
      {{Form::label('qr_room_id', 'qr_room_id')}}
      {{Form::text('qr_room_id', $qr_room_id , ['class' => 'form-control', 'placeholder' => '' ] )}}
    </div>


    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}



@endsection
