@extends('layouts.app')


  @section('content')
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Manage Tags</div>

                  <div class="card-body">
                      @if (session('status'))
                          <div class="alert alert-success" role="alert">
                              {{ session('status') }}
                          </div>
                      @endif

                      <!---You are logged in!--->

                      <div class="panel-body">
                        <div>
                          <div class="float-md-left"><h1>Tags</h1> </div>
                          <div class="float-md-right"><a href="{{ URL::to('/') }}/tags/create" class="btn btn-primary">Create Tag</a> </div>
                        </div>

                        @if(count($tags)>0)
                          <table class="table table-striped">
                              <tr>
                                <th>Tag Name-Code</th>
                                <th></th>
                                <th></th>
                              </tr>
                              @foreach($tags as $tag)
                              <tr>
                                <td>
                                  <a href="{{ URL::to('/') }}/tags/{{$tag->id}}">
                                    Name: {{$tag->tag_name}} <br>
                                    Code: {{$tag->tag_code}}
                                  </a>
                                </td>
                                <td><a href="{{ URL::to('/') }}/tags/{{$tag->id}}/edit" class="btn btn-warning">Edit</a></td>
                                <td>
                                  {!!Form::open(['action' => ['TagsController@destroy', $tag->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-danger'] )}}
                                  {!!Form::close()!!}
                                </td>
                              </tr>
                              @endforeach
                          </table>
                          {{$tags->links()}}
                        @else
                          <br><br><br>
                          <p>There are no registered tags.</p>
                        @endif

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  @endsection
