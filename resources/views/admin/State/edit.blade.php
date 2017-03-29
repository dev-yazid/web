@extends('layouts.admin')
@section('content')
<?php // echo "<pre>"; print_r($country); ?>
<div class="panel-body">

    {!! Form::model($state,array('route' => array('admin.state.update', $state->id),'files'=>true,'class'=>'form-horizontal','method'=>'PUT','id'=>'state')) !!}

    <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('country_id', 'Country', array('class' => 'required')); !!}
        {!! Form::select('country_id', $country, null,array('class' => 'form-control select2')) !!}  
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('name', 'State' , array('class' => 'required')); !!}
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
        <a class="btn btn-default" href="{{ url('/admin/state')}}">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div>

@endsection