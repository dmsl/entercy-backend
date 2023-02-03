@extends('layouts.app')

@section('content')

  <h1><u>Create QR Room</u></h1>
<br>
  {!! Form::open(['action' => 'Qr_roomsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    
    
    <div class="form-group">
      {{Form::label('name', 'Name')}}
      {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'] )}}
    </div>
    <div class="form-group ">
      {{Form::label('qr_code', 'QR Code')}}
      {{Form::textarea('qr_code', '', ['class' => 'form-control', 'placeholder' => 'Text'] )}}
    </div>
    <div class="form-group" hidden>
      {{Form::label('poiid', 'poiid')}}
      {{Form::text('poiid', $poiid , ['class' => 'form-control', 'placeholder' => '' ] )}}
    </div>

 
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}



@endsection
