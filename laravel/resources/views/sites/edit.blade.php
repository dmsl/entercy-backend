@extends('layouts.app')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <h1><u>Edit Site</u></h1>

  {!! Form::open(['action' => ['SitesController@update', $site->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

  <br>
  <div class="form-group">
      {{Form::label('name', 'Name in English')}}
      {{Form::text('name', $site->name, ['class' => 'form-control', 'placeholder' => 'Name'] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_gr', 'Name in Greek')}}
      {{Form::text('name_gr',$site->name_gr , ['class' => 'form-control ', 'placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ru', 'Name in Russian')}}
      {{Form::text('name_ru',$site->name_ru, ['class' => 'form-control', 'placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_it', 'Name in Italian')}}
      {{Form::text('name_it', $site->name_it, ['class' => 'form-control', 'placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_fr', 'Name in French')}}
      {{Form::text('name_fr', $site->name_fr, ['class' => 'form-control', 'placeholder' => ''] )}}
    </div>
    <div class="form-group">
      {{Form::label('name_ge', 'Name in German')}}
      {{Form::text('name_ge', $site->name_ge, ['class' => 'form-control', 'placeholder' => ''] )}}
    </div>

  <div class="form-group">
      {{Form::label('district', 'District')}}
      <br>
      <select id="typeOption" class="browser-default custom-select form-control" name="district" required >
        <option selected disabled >Please select District</option>
        @foreach($cydistrict as $cydistrict)
          @if($cydistrict->name != "Cover District Image")
            <option value="{{$cydistrict->id}}">{{$cydistrict->name}}</option>
          @endif
        @endforeach
      </select>
    </div>
  
  <div class="form-group">
    {{Form::label('town', 'Town in English')}}
    {{Form::text('town', $site->town, ['class' => 'form-control', 'placeholder' => 'Town in English'] )}}
  </div>
  <div class="form-group">
    {{Form::label('town_gr', 'Town in Greek')}}
    {{Form::text('town_gr', $site->town_gr, ['class' => 'form-control', 'placeholder' => 'Town in Greek'] )}}
  </div>
  <div class="form-group">
    {{Form::label('town_ru', 'Town in Russian')}}
    {{Form::text('town_ru', $site->town_ru, ['class' => 'form-control', 'placeholder' => 'Town in Russian'] )}}
  </div>
  <div class="form-group">
    {{Form::label('town_it', 'Town in Italian')}}
    {{Form::text('town_it', $site->town_it, ['class' => 'form-control', 'placeholder' => 'Town in Italian'] )}}
  </div>
  <div class="form-group">
    {{Form::label('town_fr', 'Town in French')}}
    {{Form::text('town_fr', $site->town_fr, ['class' => 'form-control', 'placeholder' => 'Town in French'] )}}
  </div>
  <div class="form-group">
    {{Form::label('town_ge', 'Town in German')}}
    {{Form::text('town_ge', $site->town_ge, ['class' => 'form-control', 'placeholder' => 'Town in German'] )}}
  </div>
  
  <div class="form-group">
      {{Form::label('category', 'Category')}}
      <br>
      <select id="typeOption2" class="browser-default custom-select form-control" name="category" required >
        <option selected disabled >Please select Category</option>
        @foreach($sitecategory as $sitecategory)
          @if($sitecategory->name != "Cover Category Image")
            <option value="{{$sitecategory->id}}">{{$sitecategory->name}}</option>
          @endif
        @endforeach
      </select>
    </div>
  <div class="form-group">
    {{Form::label('fee', 'Charging Fee')}}
    {{Form::text('fee', $site->fee, ['class' => 'form-control', 'placeholder' => 'Charging Fee'] )}}
  </div>
  <div class="form-group">
    {{Form::label('contact_tel', 'Telephone')}}
    {{Form::text('contact_tel', $site->contact_tel, ['class' => 'form-control', 'placeholder' => 'Telephone'] )}}
  </div>
  <div class="form-group">
    {{Form::label('url', 'Website')}}
    {{Form::text('url', $site->url, ['class' => 'form-control', 'placeholder' => 'Website'] )}}
  </div>

    <div class="form-group">
      {{Form::label('path', 'Please select an image/thumbnail:')}}
      <br>
      {{Form::file('path')}}
      <br><br>
    </div>
    
    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}



<script>

  $( document ).ready(function() {

    document.getElementById('typeOption').value = '{{$cydistrict_current->id}}'; //preselect type option
    document.getElementById('typeOption2').value = '{{$sitecategory_current->id}}'; //preselect type option
    
  });

</script>

@endsection
