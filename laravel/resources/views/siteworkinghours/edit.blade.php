@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/sites/{{$siteworkinghour->site_id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>Edit Working Hours</u></h1>
<br>
  {!! Form::open(['action' => ['SiteworkinghoursController@update', $siteworkinghour->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    
    <div class="form-group">
      {{Form::label('day', 'Day in English (description)')}}
      {{Form::text('day', $siteworkinghour->day, ['class' => 'form-control', 'placeholder' => 'Day in English (description)'] )}}
    </div>
     <div class="form-group">
      {{Form::label('day_gr', 'Day in Greek (description)')}}
      {{Form::text('day_gr',$siteworkinghour->day_gr , ['class' => 'form-control', 'placeholder' => 'Day in Greek (description)'] )}}
    </div>
    <div class="form-group">
      {{Form::label('day_ru', 'Day in Russian (description)')}}
      {{Form::text('day_ru',$siteworkinghour->day_ru, ['class' => 'form-control', 'placeholder' => 'Day in Russian (description)'] )}}
    </div>
    <div class="form-group">
      {{Form::label('day_it', 'Day in Italian (description)')}}
      {{Form::text('day_it', $siteworkinghour->day_it, ['class' => 'form-control', 'placeholder' => 'Day in Italian (description)'] )}}
    </div>
    <div class="form-group">
      {{Form::label('day_fr', 'Day in French (description)')}}
      {{Form::text('day_fr', $siteworkinghour->day_fr, ['class' => 'form-control', 'placeholder' => 'Day in French (description)'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Day in German (description)')}}
      {{Form::text('day_ge', $siteworkinghour->day_ge, ['class' => 'form-control', 'placeholder' => 'Day in German (description)'] )}}
    </div>

    <div class="form-group time ">
      {{Form::label('time', 'Open/Close Time')}}
      <div class="row">
          <div class="col-md">
            {{Form::label('opentime', 'Open Time:')}}
            {{Form::time('opentime', $siteworkinghour->open_time, ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('closetime', 'Close Time:')}}
            {{Form::time('closetime',  $siteworkinghour->close_time, ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
      </div>
    </div>

    <div class="form-group time ">
      {{Form::label('breaktime', 'Break Time')}}
      <div class="row">
          <div class="col-md">
            {{Form::label('breaktimestart', 'Start of a Break-Time:')}}
            {{Form::time('breaktimestart',  $siteworkinghour->break_time_start, ['class' => 'form-control','placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('breaktimeend', 'End of a Break-Time:')}}
            {{Form::time('breaktimeend',  $siteworkinghour->break_time_end, ['class' => 'form-control', 'placeholder' => ''] )}}
          </div>
      </div>
    </div>
    <br>

    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}


@endsection
