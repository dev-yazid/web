@extends('layouts.admin')
@section('content')

<div class="panel-body">                
{!! Form::open(array('url'=>'/admin/city','files'=>true,'class'=>'form-horizontal','method'=>'POST','id'=>'city')) !!}

     <!-- <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('state_id', 'State', array('class' => 'required')); !!}
        {!! Form::select('state_id', $states, null,array('class' => 'form-control select2')) !!}  
        </div>
    </div> -->

    <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('name', 'City' , array('class' => 'required')); !!}
        {!! Form::text('name',null,array('class'=>'form-control' )) !!}
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
        <a class="btn btn-default" href="{{ url('/admin/city')}}">Cancel</a> 
        </div>
    </div>

    {!! Form::close() !!}
</div>

@endsection