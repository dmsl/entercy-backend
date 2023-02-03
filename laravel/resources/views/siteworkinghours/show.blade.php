@extends('layouts.app')

@section('content')
<a href="{{ URL::to('/') }}/sites/{{$siteworkinghour->site_id}}" class="btn btn-primary">Go Back</a>
<br><br><br>
  <h1><u>{{$siteworkinghour->day}}</u></h1>

  <br>
  <div class="form-group">
      {{Form::label('day', 'Day in English (description)')}}
      {{Form::text('day', $siteworkinghour->day, ['class' => 'form-control','disabled', 'placeholder' => ''] )}}
    </div>
     <div class="form-group">
      {{Form::label('day_gr', 'Day in Greek (description)')}}
      {{Form::text('day_gr',$siteworkinghour->day_gr , ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('day_ru', 'Day in Russian (description)')}}
      {{Form::text('day_ru',$siteworkinghour->day_ru, ['class' => 'form-control','disabled', 'placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('day_it', 'Day in Italian (description)')}}
      {{Form::text('day_it', $siteworkinghour->day_it, ['class' => 'form-control','disabled', 'placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('day_fr', 'Day in French (description)')}}
      {{Form::text('day_fr', $siteworkinghour->day_fr, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Day in German (description)')}}
      {{Form::text('day_ge', $siteworkinghour->day_ge, ['class' => 'form-control', 'disabled','placeholder' => ''] )}}
    </div>
    <br>

   
    <div class="form-group time ">
      {{Form::label('time', 'Open/Close Time')}}
      <div class="row">
          <div class="col-md">
            {{Form::label('opentime', 'Open Time:')}}
            {{Form::time('opentime', $siteworkinghour->open_time, ['class' => 'form-control', 'disabled', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('closetime', 'Close Time:')}}
            {{Form::time('closetime',  $siteworkinghour->close_time, ['class' => 'form-control','disabled', 'placeholder' => ''] )}}
          </div>
      </div>
    </div>

    <div class="form-group time ">
      {{Form::label('breaktime', 'Break Time')}}
      <div class="row">
          <div class="col-md">
            {{Form::label('breaktimestart', 'Start of a Break-Time:')}}
            {{Form::time('breaktimestart',  $siteworkinghour->break_time_start, ['class' => 'form-control','disabled', 'placeholder' => ''] )}}
          </div>
          <div class="col-md">
            {{Form::label('breaktimeend', 'End of a Break-Time:')}}
            {{Form::time('breaktimeend',  $siteworkinghour->break_time_end, ['class' => 'form-control','disabled', 'placeholder' => ''] )}}
          </div>
      </div>
    </div>

  <hr>
  <small>Created on {{$siteworkinghour->created_at}} </small>
  <hr>


      <a href="{{ URL::to('/') }}/siteworkinghours/{{$siteworkinghour->id}}/edit" class="btn btn-warning">Edit</a>

      <div class="float-right">
        {!!Form::open(['action' => ['SiteworkinghoursController@destroy', $siteworkinghour->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>


@endsection
