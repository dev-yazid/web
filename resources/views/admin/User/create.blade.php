@extends('layouts.admin')
@section('content')
<div class="panel-body">

    {!! Form::open(array('url'=>'/admin/user','files'=>true,'class'=>'form-horizontal','method'=>'POST','id'=>'user')) !!}        

    <div class="form-group">
        <div class="col-md-12">
            {!! Form::label('name', 'Name' , array('class' => 'required')); !!}
            {!! Form::text('name',null,array('class'=>'form-control','maxlength' => 100)) !!}
        </div>        
    </div>
     
   <!--  <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('password', 'Password' , array('class' => 'required')); !!}
            {!! Form::text('password',null,array('class'=>'form-control')) !!}
        </div>
        <div class="col-md-6">
            {!! Form::label('Confirm Password', 'Confirm Password' , array('class' => 'required')); !!}
            {!! Form::text('confirmpassword',null,array('class'=>'form-control')) !!}
        </div>
    </div> -->
    
    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('email', 'Email', array('class' => 'required')); !!}
            {!! Form::text('email',null,array('class'=>'form-control','maxlength' => 100)) !!}
        </div>
        <div class="col-md-6">
            {!! Form::label('status', 'User Status' , array('class' => 'required')); !!}
            {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), null, array('class' => 'form-control select2')) !!}
        </div>               
    </div>

    <div class="sellerInfo">
        <div class="form-group">        
            <div class="col-md-6">
                <h4>Shop Details</h4>
            </div>
        </div>
        <div class="form-group"> 
            <div class="col-md-12">
                {!! Form::label('seller_name', 'Seller Name' , array('class' => 'required')); !!}
                {!! Form::text('seller_name',null,array('class'=>'form-control','maxlength' => 100)) !!}
            </div>
        </div>  
        <div class="form-group"> 
            <div class="col-md-6">
                {!! Form::label('shop_name', 'Shop Name', array('class' => 'required')); !!}
                {!! Form::text('shop_name',null,array('class'=>'form-control','maxlength' => 100)) !!}
            </div>
            <div class=" col-md-6">            
                {!! Form::label('shop_mobile', 'Shop Mobile', array('class' => 'required')); !!}
                {!! Form::text('shop_mobile',null,array('class'=>'form-control','maxlength' => 12)) !!}
            </div>            
        </div>

        <div class="form-group">
            <div class="col-md-6">
                {!! Form::label('shop_address', 'Shop Address', array('class' => 'required')); !!}
                {!! Form::text('shop_address',null,array('class'=>'form-control','maxlength' => 100)) !!}
            </div>
            <div class="col-md-3">            
                {!! Form::label('shop_city', 'City', array('class' => 'required')); !!}
                {!! Form::select('shop_city',$cities, null, ['class' => 'form-control select2']) !!}
            </div>
            <div class="col-md-3">            
                {!! Form::label('shop_zipcode', 'Zipcode'); !!}
                {!! Form::text('shop_zipcode',null,array('class'=>'form-control','maxlength' => 5)) !!}
            </div>       
        </div>

        <div class="form-group">
            <div class="col-md-3">
                {!! Form::label('shop_start_time', 'Shop Start Time', array('class' => 'required')); !!}
                {!! Form::text('shop_start_time',null,array('class'=>'form-control','maxlength' => 8)) !!}
                 </br>
                <i>Ex- 10:00 AM Or 12:00 PM</i>
            </div>
            <div class=" col-md-3">            
                {!! Form::label('shop_close_time', 'Shop Close Time', array('class' => 'required')); !!}
                {!! Form::text('shop_close_time',null,array('class'=>'form-control','maxlength' => 8)) !!}
                 </br>
                <i>Ex- 10:00 AM Or 12:00 PM</i>
            </div>            
            <div class="col-md-6">
                {!! Form::label('map_url', 'Google (Lat, Long)' , array('class' => 'required')); !!}
                {!! Form::text('map_url', null, ['class' => 'form-control','maxlength' => 50]) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                {!! Form::label('shop_document', 'Shop Document / Licence', array('class' => 'required')); !!}
                {!! Form::file('shop_document',null,array('class'=>'form-control')) !!}
                </br>
                <i> ( doc, pdf, png, jpg, jpeg and Max 1 MB Only )</i>
            </div>
        </div>
    </div>  

    <div class="form-group">
        <div class="col-md-12">  
            {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" href="{{ url('/admin/user')}}">Cancel</a>
        </div>
    </div>           
{!! Form::close() !!}
</div>

@endsection