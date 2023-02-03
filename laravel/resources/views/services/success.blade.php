@extends('layouts.appServices')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<div class="jumbotron">
	<!---<img style="width:100%" src="https://www.imhbusiness.com/media/zoo/images/1920X550_2a6a99f26408661fd20e8e1a8d4d3b2e.jpg">--->
	<img style="width:100%;" src="{{ URL::to('/') }}/storage/media/ServicesForm/header.jpg">
	<br><br>
  <h1 class="display-4">Successfully registered establishment!</h1>
  <p class="lead">Interest for inclusion in enterCY platform</p>
  <hr class="my-4">
 
</div>
  
@endsection
