@extends('layouts.app')

@section('content')

<a href="{{ URL::to('/') }}/tags" class="btn btn-primary">Go Back</a>
<br><br>

  <h1><u>Tag Details</u></h1>
<br>
   
    <div class="form-group">
      {{Form::label('tagcode', 'Tag Code:')}}
      {{Form::text('tagcode', $tag->tag_code, ['class' => 'form-control','disabled', 'placeholder' => 'Tag_Code'] )}}
    </div>

    <div class="form-group">
      {{Form::label('tagname', 'Tag Name:')}}
      {{Form::text('tagname', $tag->tag_name, ['class' => 'form-control','disabled', 'placeholder' => 'TagName'] )}}
    </div> 

     <hr>
      <a href="{{ URL::to('/') }}/tags/{{$tag->id}}/edit" class="btn btn-warning">Edit Tag</a>

      <div class="float-right">
        {!!Form::open(['action' => ['TagsController@destroy', $tag->id], 'method' => 'POST', 'class' => 'pull-right' , 'onsubmit' => "return confirm('Please confirm deletion')" ])!!}
          {{Form::hidden('_method', 'DELETE')}}
          {{Form::submit('Delete Tag', ['class' => 'btn btn-danger'] )}}
        {!!Form::close()!!}
      </div>

   
@endsection
