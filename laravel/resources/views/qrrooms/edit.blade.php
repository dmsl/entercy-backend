@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/pois/{{$qr_room->poi_id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Edit Qrroom</u></h1>
<br>
  {!! Form::open(['action' => ['Qr_roomsController@update', $qr_room->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}


    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', $qr_room->name, ['class' => 'form-control', 'placeholder' => 'Name'] )}}
    </div>
    <div class="form-group ">
        {{Form::label('qr_code', 'QR Code')}}
      {{Form::textarea('qr_code', $qr_room->qr_code, ['class' => 'form-control', 'placeholder' => 'Text'] )}}
    </div>
    <div class="form-group" hidden>
        {{Form::label('poiid', 'poiid')}}
        {{Form::text('poiid', $qr_room->poi_id , ['class' => 'form-control', 'placeholder' => '' ] )}}
      </div>
    <br>

    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}



@endsection
