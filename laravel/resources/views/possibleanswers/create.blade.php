@extends('layouts.app')

@section('content')

<a href="{{ URL::to('/') }}/questions/{{$question_id}}" class="btn btn-primary">Back to Question</a>
<br><br>

  <h1><u>Create Answer</u></h1>

  {!! Form::open(['action' => 'PossibleanswersController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    <br>

    <div class="form-group">
      {{Form::label('order_num', 'Order number')}}
      {{Form::text('order_num', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => ''] )}}
    </div>

    <div class="form-group">
      {{Form::label('answer', 'Answer in English')}}
      {{Form::textarea('answer_en', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Answer in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in Greek')}}
      {{Form::textarea('answer_gr', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Answer in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in Russian')}}
      {{Form::textarea('answer_ru', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Answer in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in Italian')}}
      {{Form::textarea('answer_it', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Answer in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in French')}}
      {{Form::textarea('answer_fr', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Answer in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in German')}}
      {{Form::textarea('answer_ge', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Answer in German'] )}}
    </div>   

    <div class="form-group">
      <br>
      {{Form::label('path_img', 'Please select an image:')}}
      <br>
      {{Form::file('path_img')}}      
    </div>

    <div class="form-group" hidden>
      {{Form::label('question_id', 'question_id')}}
      {{Form::text('question_id', $question_id , ['class' => 'form-control', 'placeholder' => '' ] )}}
    </div>
    <hr>
  
    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}
<hr>
@endsection
