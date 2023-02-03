@extends('layouts.app')

@section('content')

<a href="{{ URL::to('/') }}/questions" class="btn btn-primary">Back to Questions</a>


<br><br>
  <h1><u>Question View</u>  </h1>

   <br>

    <div class="form-group">
        {{Form::label('type', 'Please select compulsory/optional')}}
        <select id="compulsory" name="compulsory" class="browser-default custom-select form-control" disabled>
          <option selected disabled >{{$questions->compulsory}}</option>
          <option value="Compulsory">Compulsory</option>
          <option value="Optional">Optional</option>
        </select>
    </div>

    <div class="form-group">
        {{Form::label('type', 'Please select question type')}}
        <select id="type" name="type" class="browser-default custom-select form-control" disabled>
          <option selected disabled >{{$questions->type}}</option>
          <option value="Multiple choice">Multiple choice</option>
          <option value="Multiple selection">Multiple selection</option>
          <option value="Rate">Rate</option>
        </select>
    </div>
    <div class="form-group">
        {{Form::label('order_num', 'Order number')}}
        {{Form::text('order_num', $questions->order_num, ['class' => 'form-control','disabled', 'rows'=>'3', 'placeholder' => ''] )}}
      </div>
    <div class="form-group">
      {{Form::label('question', 'Question in English')}}
      {{Form::textarea('question_en', $questions->question_en, ['class' => 'form-control', 'disabled','rows'=>'3', 'placeholder' => 'Question in English'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in Greek')}}
      {{Form::textarea('question_gr', $questions->question_gr, ['class' => 'form-control', 'disabled','rows'=>'3', 'placeholder' => 'Question in Greek'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in Russian')}}
      {{Form::textarea('question_ru', $questions->question_ru, ['class' => 'form-control', 'disabled','rows'=>'3', 'placeholder' => 'Question in Russian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in Italian')}}
      {{Form::textarea('question_it', $questions->question_it, ['class' => 'form-control', 'disabled','rows'=>'3', 'placeholder' => 'Question in Italian'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in French')}}
      {{Form::textarea('question_fr', $questions->question_fr, ['class' => 'form-control', 'disabled','rows'=>'3', 'placeholder' => 'Question in French'] )}}
    </div>
    <div class="form-group">
      {{Form::label('question', 'Question in German')}}
      {{Form::textarea('question_ge', $questions->question_ge, ['class' => 'form-control', 'disabled','rows'=>'3', 'placeholder' => 'Question in German'] )}}
    </div>


  <hr>
  <small>Created on {{$questions->created_at}}  </small>
  <hr>

  <a href="{{ URL::to('/') }}/questions/{{$questions->id}}/edit" class="btn btn-warning">Edit</a>

      <div class="float-right">
        {!!Form::open(['action' => ['QuestionsController@destroy', $questions->id], 'method' => 'POST', 'class' => 'pull-right', 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>

    <br>
   <hr>


    <div><h1><u>Manage Possible Answers</u></h1> </div>

    <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Possible Answers</div>
                  <div class="card-body">
                      <div class="panel-body">
                        <div>
                          <div class="float-md-left"><h1>Possible Answers</h1> </div>
                          <div class="float-md-right"><a href="{{ URL::to('/') }}/create-possibleanswer/{{$questions->id}}" class="btn btn-primary">Create Answer</a> </div>
                        </div>

                        @if(count($possibleanswers)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Name</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($possibleanswers as $possibleanswers)
                              <tr>
                                <td>
                                  <h5>
                                    <a href="{{ URL::to('/') }}/possibleanswers/{{$possibleanswers->id}}">{{$possibleanswers->answer_en}}
                                    </a>
                                  </h5>
                                </td>
                                <td>
                                  <a href="{{ URL::to('/') }}/possibleanswers/{{$possibleanswers->id}}/edit" class="btn btn-warning">Edit</a>
                                </td>
                                <td>
                                   {!!Form::open(['action' => ['PossibleanswersController@destroy', $possibleanswers->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')"])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                  {!!Form::close()!!}
                                </td>
                              </tr>
                              @endforeach
                          </table>
                        @else
                          <br><br><br>
                          <p>There are no registered answers.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
<br>


@endsection
