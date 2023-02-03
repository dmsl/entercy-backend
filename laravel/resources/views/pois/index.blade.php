@extends('layouts.app')

@section('content')
  <h1>POIs</h1>

  @if(count($pois)>0)
    @foreach($pois as $poi)
      <div class="card card-body bg-light">
        <div class="row">
          <div class="col-md-4 col-sm-4">
            <img style="width:100%" src="{{ URL::to('/') }}/storage/cover_images/{{$poi->cover_image}}">
          </div>
          <div class="col-md-8 col-sm-8">
            <h3><a href="{{ URL::to('/') }}/pois/{{$poi->id}}">{{$poi->name}}</a></h3>
              <small>Created on {{$poi->created_at}}</small>
          </div>
        </div>
      </div>
      <br>
    @endforeach
    {{$pois->links()}}
  @else
    <p>Nothing found</p>
  @endif

@endsection
