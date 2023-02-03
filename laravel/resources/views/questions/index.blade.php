@extends('layouts.app')


  @section('content')
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Manage Questionnaire</div>

                  <div class="card-body">
                      @if (session('status'))
                          <div class="alert alert-success" role="alert">
                              {{ session('status') }}
                          </div>
                      @endif

                      <!---You are logged in!--->

                      <div class="panel-body">
                        <div>
                          <div class="float-md-left"><h1>Questionnaire</h1> </div>
                          <div class="float-md-right"><a href="{{ URL::to('/') }}/questions/create" class="btn btn-primary">Create Question</a> </div>
                        </div>

                        @if(count($questions)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Name</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($questions as $questions)
                              <tr>
                                <td><a href="{{ URL::to('/') }}/questions/{{$questions->id}}">{{$questions->question_en}}</a></td>
                                <td><a href="{{ URL::to('/') }}/questions/{{$questions->id}}/edit" class="btn btn-warning">Edit</a></td>
                                <td>
                                  {!!Form::open(['action' => ['QuestionsController@destroy', $questions->id], 'method' => 'POST', 'class' => 'pull-right', 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                  {!!Form::close()!!}
                                </td>
                              </tr>
                              @endforeach                              
                          </table>
                        @else
                          <br><br><br>
                          <p>There are no registered questions.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  @endsection

