@extends('layouts.app')

@section('content')
  <h1><u>Create Thematic Route</u></h1>

  {!! Form::open(['action' => 'ThematicroutesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    <br>
    <div class="form-group">
      {{Form::label('path_img', 'Please select an image:')}}
      <br>
      {{Form::file('path_img')}}
      <br><br>
    </div>
    <hr>

    <div class="form-group">
      {{Form::label('path_thumbnail', 'Please select an thumbnail:')}}
      <br>
      {{Form::file('path_thumbnail')}}
      <br><br>
    </div>
    <hr>

    <div class="form-group">
      {{Form::label('name', 'Name in English')}}
      {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name in English'] )}}
    </div>
     <div class="form-group">
      {{Form::label('name_gr', 'Name in Greek')}}
      {{Form::text('name_gr','' , ['class' => 'form-control', 'placeholder' => 'Name in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ru', 'Name in Russian')}}
      {{Form::text('name_ru','', ['class' => 'form-control', 'placeholder' => 'Name in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_it', 'Name in Italian')}}
      {{Form::text('name_it', '', ['class' => 'form-control', 'placeholder' => 'Name in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_fr', 'Name in French')}}
      {{Form::text('name_fr', '', ['class' => 'form-control', 'placeholder' => 'Name in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Name in German')}}
      {{Form::text('name_ge', '', ['class' => 'form-control', 'placeholder' => 'Name in German'] )}}
    </div>

    <div class="form-group">
      {{Form::label('description', 'Description in English')}}
      {{Form::textarea('description', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description', 'Description in Greek')}}
      {{Form::textarea('description_gr', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description', 'Description in Russian')}}
      {{Form::textarea('description_ru', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description', 'Description in Italian')}}
      {{Form::textarea('description_it', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description', 'Description in French')}}
      {{Form::textarea('description_fr', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description', 'Description in German')}}
      {{Form::textarea('description_ge', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Description in German'] )}}
    </div>   
    <hr>
  
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}
<hr>
@endsection
