@extends('layouts.admin')
@section('content')

<div class="panel-body">                
{!! Form::open(array('url'=>'/admin/brand','files'=>true,'class'=>'form-horizontal','method'=>'POST','id'=>'brand')) !!}

    <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('brand', 'Brand' , array('class' => 'required')); !!}
        {!! Form::text('brand',null,array('class'=>'form-control')) !!}
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-12">
            {!! Form::label('Image', 'Brand Image' , array('class' => 'required')); !!}
            {!! Form::file('image',null,array('class'=>'form-control')) !!}            
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6">
        {!! Form::label('status', 'Status' , array('class' => 'required')); !!}
        {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), null, array('class' => 'form-control select2')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
        {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
        <a class="btn btn-default" href="{{ url('/admin/brand')}}">Cancel</a>
        </div> 
    </div>

    {!! Form::close() !!}
</div>

@endsection