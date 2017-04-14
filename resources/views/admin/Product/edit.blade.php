@extends('layouts.admin')
@section('content')

<div class="panel-body">

    {!! Form::model($qual, array('route' => array('admin.product.update', $qual->id),'class'=>'form-horizontal','method'=>'PUT','id'=>'prod')) !!}

    <div class="form-group">
        <div class="col-md-6">
        {!! Form::label('brand', 'Product Brand' , array('class' => 'required')); !!}
        {!! Form::select('brand', $brandname, null, array('class' => 'form-control select2')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6">
        {!! Form::label('pname', 'Product Name' , array('class' => 'required')); !!}
        {!! Form::text('pname',null,array('class'=>'form-control' )) !!}
        </div>

       <!--  <div class="col-md-6">
        {!! Form::label('year', 'Product Year' , array('class' => 'required')); !!}
        {!! Form::select('year', $year, null, array('class' => 'form-control select2')) !!}
        </div> -->
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
        <a class="btn btn-default" href="{{ url('/admin/product')}}">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div> 

@endsection