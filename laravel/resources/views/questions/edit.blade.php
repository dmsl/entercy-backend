@extends('layouts.app')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

   <a href="{{ URL::to('/') }}/questions" class="btn btn-primary">Back to Questions</a>
   <br><br><br>

  <h1><u>Edit Question:</u></h1>

  {!! Form::open(['action' => ['QuestionsController@update', $questions->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

   <div class="form-group">
        {{Form::label('type', 'Please select compulsory/optional')}}
        <select id="compulsory" name="compulsory" class="browser-default custom-select form-control">
          <option selected disabled >Please select compulsory/optional </option>
          <option value="Compulsory">Compulsory</option>
          <option value="Optional">Optional</option>
        </select>
    </div>

    <div class="form-group">
        {{Form::label('type', 'Please select question type')}}
        <select id="type" name="type" class="browser-default custom-select form-control">
          <option selected disabled >Please select question type </option>
          <option value="Multiple choice">Multiple choice</option>
          <option value="Multiple selection">Multiple selection</option>
          <option value="Rate">Rate</option>
        </select>
    </div>
    <div class="form-group">
        {{Form::label('order_num', 'Order number')}}
        {{Form::text('order_num', $questions->order_num, ['class' => 'form-control', 'rows'=>'3', 'placeholder' => ''] )}}
      </div>
    <div class="form-group">
      {{Form::label('question', 'Question in English')}}
      {{Form::textarea('question_en', $questions->question_en, ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in Greek')}}
      {{Form::textarea('question_gr', $questions->question_gr, ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in Russian')}}
      {{Form::textarea('question_ru', $questions->question_ru, ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in Italian')}}
      {{Form::textarea('question_it', $questions->question_it, ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in French')}}
      {{Form::textarea('question_fr', $questions->question_fr, ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in German')}}
      {{Form::textarea('question_ge', $questions->question_ge, ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in German'] )}}
    </div>

    <hr>

    <div class="float-left">
        {{Form::hidden('_method', 'PUT')}}
      {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
      {!! Form::close() !!}
     </div>

   <br><br>
   <hr>

<script>

  $( document ).ready(function() {

    document.getElementById('compulsory').value = '{{$questions->compulsory}}'; //preselect compulsory option
    document.getElementById('type').value = '{{$questions->type}}'; //preselect type option

  });

</script>

@endsection
