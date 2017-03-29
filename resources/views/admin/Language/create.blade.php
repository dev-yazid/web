@extends('layouts.admin')
@section('content')

<div class="panel-body">                
{!! Form::open(array('url'=>'/admin/language','files'=>true,'class'=>'form-horizontal','method'=>'POST','id'=>'language')) !!}
    
    <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('label', 'English Label' , array('class' => 'required')); !!}
        {!! Form::text('label',null,array('class'=>'form-control')) !!}
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('changed_label', 'Arabic Label' , array('class' => 'required')); !!}
        {!! Form::text('changed_label',null,array('class'=>'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('page_url', 'Url / Title'); !!}
        {!! Form::text('page_url',null,array('class'=>'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('comments', 'Comments'); !!}
        {!! Form::text('comments',null,array('class'=>'form-control')) !!}
        </div>
    </div>    

    <div class="form-group">
        <div class="col-md-12">
        {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
        <a class="btn btn-default" href="{{ url('/admin/language')}}">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div>

@endsection