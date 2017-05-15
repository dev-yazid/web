@extends('layouts.admin')
@section('content')

<div class="panel-body">

    {!! Form::model($page,array('route' => array('admin.cms.update', $page->id),'files'=>true,'class'=>'form-horizontal','method'=>'PUT','id'=>'cms')) !!}
    <div class="form-group">
        {!! Form::label('title', 'Title'); !!}
        {!! Form::text('title',null,array('class'=>'form-control')) !!}
    </div>

    <div class="form-group">
        {!! Form::label('content', 'Page Description'); !!}
        {!! Form::textarea('description',null,array('class'=>'ckeditor form-control', 'rows' => '6', 'cols' => '30')) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
        <a class="btn btn-default" href="{{ url('/admin/cms')}}">Cancel</a> 
    </div>

    {!! Form::close() !!}
</div>

@endsection