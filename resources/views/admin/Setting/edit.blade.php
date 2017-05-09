@extends('layouts.admin')
@section('content')

<div class="panel-body">

    {!! Form::model($page,array('route' => array('admin.setting.update', $page->id),'files'=>true,'class'=>'form-horizontal','method'=>'PUT','id'=>'brand')) !!}
    <?php if($page->status == 1) { ?>
    <div class="form-group">
        <div class="col-md-6">
        {!! Form::label('status', 'Seller Activation Status' , array('class' => 'required')); !!}
        {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), null, array('class' => 'form-control select2')) !!}
        </div>
    </div>
    <?php } else { ?>
    <div class="form-group">
        <div class="col-md-6">
        {!! Form::label('status', 'Seller Activation Status' , array('class' => 'required')); !!}
        {!! Form::select('status', array('0' => 'InActive', '1' => 'Active'), null, array('class' => 'form-control select2')) !!}
        </div>
    </div>
    <?php } ?>

    <div class="form-group">
        <div class="col-md-12">
        {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
        <a class="btn btn-default" href="{{ url('/admin/setting')}}">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div>

@endsection