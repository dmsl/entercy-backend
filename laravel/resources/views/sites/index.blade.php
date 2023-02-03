@extends('layouts.app')

@section('content')

@if(Auth::user()->role != 'normal')
  <div>

    <div class="float-md-left"><h1><u>Sites:</u></h1> </div>
    <div class="float-md-right"><a href="{{ URL::to('/') }}/sites/create" class="btn btn-primary">Create Site</a> </div>

  </div>
  <br><br><br>

  <div class="border  rounded" >
    <br>
    {!! Form::open(['action' => 'SitesController@search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
      <div style="margin-left: 10px;">
        <strong style="font-size: 20px;">{{Form::label('searchcriteria', 'Search Criteria')}}</strong>
        <div class="row">
            <div class="col-md-10">
              {{Form::text('keyword', '', ['class' => 'form-control', 'placeholder' => 'Search Site Name here...'] )}}
            </div>
            <div class="col-md-2">
             {{Form::submit('Search', ['class' => 'btn btn-secondary'] )}}  
             <br><br>
            </div>
        </div>
      </div>
      {!! Form::close() !!}
      <br>
  </div>
  <br><br>

    @if(count($sites)>0)
      @foreach($sites as $site)
        <div class="card card-body bg-light">
          <div class="row">
            <div class="col-md-4 col-sm-4">
              <!---<img style="width:100%" src="/storage/media/{{$site->path}}">--->
              <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$site->path}}">
            </div>
            <div class="col-md-8 col-sm-8">
              <h3><a href="{{ URL::to('/') }}/sites/{{$site->id}}">{{$site->name}}</a></h3>
              <small>Created on {{$site->created_at}} </small>
            </div>
          </div>
        </div>
        <br>
      @endforeach
      {{$sites->links()}}
    @else
      <p>Nothing found</p>
    @endif

@else
  <div>
    <br><br>
    <h1><u>Welcome to EnterCY platform!</u></h1> 
    <h2>Please wait the administrator for your account approval.</h2> 
  </div>
@endif

@endsection
