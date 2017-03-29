@extends('layouts.admin')
@section('content')

<div class="panel-body">                
{!! Form::open(array('url'=>'/admin/country','files'=>true,'class'=>'form-horizontal','method'=>'POST','id'=>'country')) !!}

   <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('name', 'Country' , array('class' => 'required')); !!}
        {!! Form::text('name',null,array('class'=>'form-control' )) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('sortname', 'Country Code' , array('class' => 'required')); !!}
        {!! Form::text('sortname',null,array('class'=>'form-control' )) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
	    {!! Form::label('status', 'Status' , array('class' => 'required')); !!}
	    {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), null, array('class' => 'form-control')) !!}
        </div>
	</div>  

    <div class="form-group">
        <div class="col-md-12">
        {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
        <a class="btn btn-default" href="{{ url('/admin/country')}}">Cancel</a> 
        </div>
    </div>

    {!! Form::close() !!}
</div>

@endsection