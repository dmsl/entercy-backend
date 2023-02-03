@extends('layouts.app')

@section('content')

  <h1><u>Add Working Hours</u></h1>
<br>
  {!! Form::open(['action' => 'SiteworkinghoursController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

      
    <div class="form-group">
      {{Form::label('day', 'Day in English (description)')}}
      {{Form::text('day', '', ['class' => 'form-control', 'placeholder' => 'Day in English (description)'] )}}
    </div>
     <div class="form-group">
      {{Form::label('day_gr', 'Day in Greek (description)')}}
      {{Form::text('day_gr','' , ['class' => 'form-control', 'placeholder' => 'Day in Greek (description)'] )}}
    </div>
    <div class="form-group">
      {{Form::label('day_ru', 'Day in Russian (description)')}}
      {{Form::text('day_ru','', ['class' => 'form-control', 'placeholder' => 'Day in Russian (description)'] )}}
    </div>
    <div class="form-group">
      {{Form::label('day_it', 'Day in Italian (description)')}}
      {{Form::text('day_it', '', ['class' => 'form-control', 'placeholder' => 'Day in Italian (description)'] )}}
    </div>
    <div class="form-group">
      {{Form::label('day_fr', 'Day in French (description)')}}
      {{Form::text('day_fr', '', ['class' => 'form-control', 'placeholder' => 'Day in French (description)'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Day in German (description)')}}
      {{Form::text('day_ge', '', ['class' => 'form-control', 'placeholder' => 'Day in German (description)'] )}}
    </div>
    <br>

    <div class="form-group time ">
      {{Form::label('time', 'Open/Close Time')}}
      <div class="row">
          <div class="col-md">
            {{Form::label('opentime', 'Open Time:')}}
            {{Form::time('opentime', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('closetime', 'Close Time:')}}
            {{Form::time('closetime', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
      </div>
    </div>

    <div class="form-group time ">
      {{Form::label('breaktime', 'Break Time')}}
      <div class="row">
          <div class="col-md">
            {{Form::label('breaktimestart', 'Start of a Break-Time:')}}
            {{Form::time('breaktimestart', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('breaktimeend', 'End of a Break-Time:')}}
            {{Form::time('breaktimeend', '', ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
      </div>
    </div>

    <div class="form-group" hidden>
      {{Form::label('siteid', 'Site ID')}}
      {{Form::text('siteid', $siteid , ['class' => 'form-control', 'placeholder' => '' ] )}}
    </div>
    <br>

    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}


@endsection
