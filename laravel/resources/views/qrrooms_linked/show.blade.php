@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/qrrooms_groups/{{$qr_room_grouped->id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Edit Qrroom Links </u></h1>
<br>


<div class="form-group">
    {{Form::label('name', 'Name')}}
    {{Form::text('name', $qr_room_link->name, ['class' => 'form-control', 'disabled' => 'Name'] )}}
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
      {{Form::label('x_position', 'X position')}}
      {{Form::number('x_position', $qr_room_link->x_position, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
    </div>
    <div class="form-group">
      {{Form::label('y_position', 'Y position')}}
      {{Form::number('y_position', $qr_room_link->y_position, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
    </div>
    <div class="form-group">
      {{Form::label('z_position', 'Z position')}}
      {{Form::number('z_position', $qr_room_link->z_position, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
    </div>

    <div class="form-group">
      {{Form::label('x_scale', 'X scale')}}
      {{Form::number('x_scale', $qr_room_link->x_scale, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
    </div>
    <div class="form-group">
      {{Form::label('y_scale', 'Y scale')}}
      {{Form::number('y_scale', $qr_room_link->y_scale, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
    </div>
    <div class="form-group">
      {{Form::label('z_scale', 'Z scale')}}
      {{Form::number('z_scale', $qr_room_link->z_scale, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
    </div>

    <div class="form-group">
      {{Form::label('x_rotation', 'X rotation')}}
      {{Form::number('x_rotation', $qr_room_link->x_rotation, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
    </div>
    <div class="form-group">
      {{Form::label('y_rotation', 'Y rotation')}}
      {{Form::number('y_rotation', $qr_room_link->y_rotation, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
    </div>
    <div class="form-group">
      {{Form::label('z_rotation', 'Z rotation')}}
      {{Form::number('z_rotation', $qr_room_link->z_rotation, ['class' => 'form-control', 'disabled' => 'Enter Number Value'] )}}
    </div>
  <div class="form-group" hidden>
    {{Form::label('qr_roomid', 'qr_roomid')}}
    {{Form::text('qr_roomid', $qr_room_link->qr_room_id , ['class' => 'form-control', 'disabled' => '' ] )}}
  </div>
  <small>Created on {{$qr_room_link->created_at}} </small>
  <hr>

      <a href="{{ URL::to('/') }}/qrrooms_linked/{{$qr_room_link->id}}/edit" class="btn btn-warning">Edits QR room</a>

      <div class="float-right">
        {!!Form::open(['action' => ['Qr_room_linksController@destroy', $qr_room_link->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete QR room', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>

      <br>
      <hr>

@endsection

