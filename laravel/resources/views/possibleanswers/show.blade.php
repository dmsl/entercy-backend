@extends('layouts.app')

@section('content')

<a href="{{ URL::to('/') }}/questions/{{$possibleanswers->question_id}}" class="btn btn-primary">Back to Question</a>


<br><br>
  <h1><u>Answer View</u>  </h1>
  
   <br>

    @if ($possibleanswers->path_img != '')
    <img style="width:100%" src="{{ URL::to('/') }}/storage/media/{{$possibleanswers->path_img}}">
   @endif
   <br><br>

      <div class="form-group">
      {{Form::label('order_num', 'Order number')}}
      {{Form::text('order_num', $possibleanswers->order_num, ['class' => 'form-control', 'rows'=>'3','disabled', 'placeholder' => ''] )}}
    </div>

    <div class="form-group">
      {{Form::label('answer', 'Answer in English')}}
      {{Form::textarea('answer_en', $possibleanswers->answer_en, ['class' => 'form-control', 'rows'=>'3','disabled', 'placeholder' => 'Answer in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in Greek')}}
      {{Form::textarea('answer_gr', $possibleanswers->answer_gr, ['class' => 'form-control', 'rows'=>'3','disabled', 'placeholder' => 'Answer in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in Russian')}}
      {{Form::textarea('answer_ru', $possibleanswers->answer_ru, ['class' => 'form-control', 'rows'=>'3', 'disabled','placeholder' => 'Answer in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in Italian')}}
      {{Form::textarea('answer_it', $possibleanswers->answer_it, ['class' => 'form-control', 'rows'=>'3', 'disabled','placeholder' => 'Answer in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in French')}}
      {{Form::textarea('answer_fr', $possibleanswers->answer_fr, ['class' => 'form-control', 'rows'=>'3','disabled', 'placeholder' => 'Answer in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('answer', 'Answer in German')}}
      {{Form::textarea('answer_ge', $possibleanswers->answer_ge, ['class' => 'form-control', 'rows'=>'3', 'disabled','placeholder' => 'Answer in German'] )}}
    </div>   

    <hr>
   

  <hr>
  <small>Created on {{$possibleanswers->created_at}}  </small>
  <hr>

  <a href="{{ URL::to('/') }}/possibleanswers/{{$possibleanswers->id}}/edit" class="btn btn-warning">Edit</a>

      <div class="float-right">
        {!!Form::open(['action' => ['PossibleanswersController@destroy', $possibleanswers->id], 'method' => 'POST', 'class' => 'pull-right', 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>

    <br>
   <hr>


@endsection
