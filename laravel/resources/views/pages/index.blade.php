@extends('layouts.app')

@section('content')
      <div class="jumbotron text-center">
      <h1> {{$title}}</h1>
      <p>DATA MANAGEMENT SYSTEMS LABORATORY </p>
      <p>The EnterCY project proposes the development of a platform, aiming to <br> promote Cyprus as a premium tourism destination by enhancing tourists’ experience.</p>
      <p><a class="btn btn-primary btn-lg" href="{{ URL::to('/') }}/login" role="button">Login</a> <a class="btn btn-success btn-lg" href="{{ URL::to('/') }}/register" role="button">Register</a> </p>
    </div>
@endsection
