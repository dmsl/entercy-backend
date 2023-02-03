@extends('layouts.app')

@section('content')

   <a href="{{ url()->previous() }}" class="btn btn-primary">Back to Service Categories</a>
   <br><br><br>

  <h1><u>Edit Service Category: {{$service_category->name}} </u></h1>

  {!! Form::open(['action' => ['ServicecategoriesController@update', $service_category->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

  @if ($service_category->path_img != '')
  <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$service_category->path_img}}">
  @endif
  <br>

   <div class="form-group">
      <br>
      {{Form::label('path_img', 'Please select an image:')}}
      <br>
      {{Form::file('path_img')}}
   </div>

   <hr>

    @if ($service_category->path_thumbnail != '')
  <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$service_category->path_thumbnail}}">
  @endif
  <br>

   <div class="form-group">
      <br>
      {{Form::label('path_thumbnail', 'Please select an thumbnail:')}}
      <br>
      {{Form::file('path_thumbnail')}}
   </div>

   <hr>

    <hr><br>

    <div class="form-group">
      {{Form::label('name_gr', 'Name in Greek')}}
      {{Form::text('name_gr', $service_category->name_gr, ['class' => 'form-control', 'placeholder' => 'Name in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ru', 'Name in Russian')}}
      {{Form::text('name_ru', $service_category->name_ru, ['class' => 'form-control', 'placeholder' => 'Name in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_it', 'Name in Italian')}}
      {{Form::text('name_it', $service_category->name_it, ['class' => 'form-control', 'placeholder' => 'Name in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_fr', 'Name in French')}}
      {{Form::text('name_fr', $service_category->name_fr, ['class' => 'form-control', 'placeholder' => 'Name in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Name in German')}}
      {{Form::text('name_ge', $service_category->name_ge, ['class' => 'form-control', 'placeholder' => 'Name in German'] )}}
    </div>

     <hr>
     <br>

     <div class="form-group">
      {{Form::label('description', 'Description in English')}}
      {{Form::textarea('description', $service_category->description, ['class' => 'form-control', 'placeholder' => 'Description  in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_gr', 'Description in Greek')}}
      {{Form::textarea('description_gr', $service_category->description_gr, ['class' => 'form-control', 'placeholder' => 'Description in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_ru', 'Description in Russian')}}
      {{Form::textarea('description_ru', $service_category->description_ru, ['class' => 'form-control', 'placeholder' => 'Description in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_it', 'Description in Italian')}}
      {{Form::textarea('description_it', $service_category->description_it, ['class' => 'form-control', 'placeholder' => 'Description in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_fr', 'Description in French')}}
      {{Form::textarea('description_fr', $service_category->description_fr, ['class' => 'form-control', 'placeholder' => 'Description in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('description_ge', 'Description in German')}}
      {{Form::textarea('description_ge', $service_category->description_ge, ['class' => 'form-control', 'placeholder' => 'Description in German'] )}}
    </div>

    <hr>

    <div class="float-left">
        {{Form::hidden('_method', 'PUT')}}
    	{{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  		{!! Form::close() !!}
     </div>

	 <br><br>
	 <hr>

@endsection
