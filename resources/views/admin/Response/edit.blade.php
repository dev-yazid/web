@extends('layouts.admin')
@section('content')

<div class="panel-body">

    {!! Form::model($community,array('route' => array('admin.brand.update', $community->id),'files'=>true,'class'=>'form-horizontal','method'=>'PUT','id'=>'cms')) !!}
    <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('brand', 'Brand' , array('class' => 'required')); !!}
        {!! Form::text('brand',null,array('class'=>'form-control')) !!}
        </div>
    </div>
     <div class="form-group">
        <div class="col-md-12">
        {!! Form::label('status', 'Status' , array('class' => 'required')); !!}
        {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), null, array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">       
        <div class="col-md-4">
            <img class="margin padding col-mg-2" src="<?php echo asset('public/asset/brand/'.$community->image) ?>">
        </div>
        <div class="col-md-8">
            {!! Form::label('Image', 'Image' , array('class' => 'required')); !!}
            {!! Form::file('image',null,array('class'=>'form-control')) !!}            
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <div class="col-md-12">
        {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
        <a class="btn btn-default" href="{{ url('/admin/brand')}}">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div>

@endsection