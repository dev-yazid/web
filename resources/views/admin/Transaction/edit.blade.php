@extends('layouts.admin')
@section('content')

<div class="panel-body">

    {!! Form::model($page,array('route' => array('admin.blog.update', $page->id),'files'=>true,'class'=>'form-horizontal','method'=>'PUT','id'=>'cms')) !!}
    <div class="form-group">
        {!! Form::label('title', 'Title'); !!}
        {!! Form::text('title',null,array('class'=>'form-control')) !!}
    </div>

    <div class="form-group">
        {!! Form::label('content', 'Blog Content'); !!}
        {!! Form::textarea('content',null,array('class'=>'ckeditor form-control', 'rows' => '6', 'cols' => '30')) !!}
    </div>

    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('postedby', 'Posted By'); !!}
            {!! Form::text('postedby',null,array('class'=>'form-control')) !!}
        </div>

        <div class="col-md-6">
            {!! Form::label('status', 'Status'); !!}
            {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), null, array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
        <a class="btn btn-default" href="{{ url('/admin/blog')}}">Cancel</a> 
    </div>

    {!! Form::close() !!}
</div>

@endsection