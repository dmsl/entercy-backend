@extends('layouts.app')

@section('content')

   <a href="{{ URL::to('/') }}/questions/{{$possibleanswers->question_id}}" class="btn btn-primary">Back to Question</a>


<br><br>

  <h1><u>Edit Answer:</u></h1>

  {!! Form::open(['action' => ['PossibleanswersController@update', $possibleanswers->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

   <div class="form-group">
      {{Form::label('order_num', 'Order number')}}
      {{Form::text('order_num', $possibleanswers->order_num, ['class' => 'form-control', 'rows'=>'3', 'placeholder' => ''] )}}
    </div>

    <div class="form-group">
      {{Form::label('answer', 'Answer in English')}}
      {{Form::textarea('answer_en', $possibleanswers->answer_en, ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Answer in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in Greek')}}
      {{Form::textarea('answer_gr', $possibleanswers->answer_gr, ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Answer in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in Russian')}}
      {{Form::textarea('answer_ru', $possibleanswers->answer_ru, ['class' => 'form-control', 'rows'=>'3','placeholder' => 'Answer in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in Italian')}}
      {{Form::textarea('answer_it', $possibleanswers->answer_it, ['class' => 'form-control', 'rows'=>'3','placeholder' => 'Answer in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in French')}}
      {{Form::textarea('answer_fr', $possibleanswers->answer_fr, ['class' => 'form-control', 'rows'=>'3','placeholder' => 'Answer in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in German')}}
      {{Form::textarea('answer_ge', $possibleanswers->answer_ge, ['class' => 'form-control', 'rows'=>'3','placeholder' => 'Answer in German'] )}}
    </div>   

   @if ($possibleanswers->path_img != '')
    <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$possibleanswers->path_img}}">
   @endif
   <br>

   <div class="form-group">
      <br>
      {{Form::label('path_img', 'Please select an image:')}}
      <br>
      {{Form::file('path_img')}}      
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
