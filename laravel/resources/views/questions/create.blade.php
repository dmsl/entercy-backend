@extends('layouts.app')

@section('content')
  <h1><u>Create Question</u></h1>

  {!! Form::open(['action' => 'QuestionsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    <br>

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
        {{Form::text('order_num', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => ''] )}}
      </div>
    <div class="form-group">
      {{Form::label('question', 'Question in English')}}
      {{Form::textarea('question_en', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in Greek')}}
      {{Form::textarea('question_gr', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in Russian')}}
      {{Form::textarea('question_ru', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in Italian')}}
      {{Form::textarea('question_it', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in French')}}
      {{Form::textarea('question_fr', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in German')}}
      {{Form::textarea('question_ge', '', ['class' => 'form-control', 'rows'=>'3', 'placeholder' => 'Question in German'] )}}
    </div>
    <hr>

    {{Form::submit('Submit', ['class' => 'btn btn-primary'] )}}
  {!! Form::close() !!}
<hr>
@endsection
