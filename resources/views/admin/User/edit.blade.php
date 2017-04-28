@extends('layouts.admin')
@section('content')
<div class="panel-body">
  {!! Form::model($user,array('route' => array('admin.user.update', $user->id),'class'=>'form-horizontal','method'=>'PUT','id'=>'user','files'=>true)) !!}

    <div class="form-group">
        <div class=" col-md-6">
              <h4>Presonal Details</h4> 
        </div>
    </div>
    
    <div class="form-group">
        <div class=" col-md-6">
            {!! Form::label('firstname', 'First Name', array('class' => 'required')); !!}
            {!! Form::text('firstname',null,array('class'=>'form-control')) !!}
        </div>

        <div class="col-md-6">
            {!! Form::label('lastname', 'Last Name' , array('class' => 'required')); !!}
            {!! Form::text('lastname',null,array('class'=>'form-control')) !!}
        </div>           
    </div>
    
    <div class="form-group">
        @if($user->usertype != "Super Admin")
            @if($isAdmin != true )
                <div class=" col-md-6">
                    {!! Form::label('email', 'Email' , array('class' => 'required')); !!}
                    {!! Form::text('email',null,array('class'=>'form-control','readonly'=>'true')) !!} 
                </div>

                <div class="col-md-6">
                    {!! Form::label('status', 'User Status' , array('class' => 'required')); !!}
                    {!! Form::select('status', array('Active' => 'Active', 'InActive' => 'InActive'), null, array('class' => 'form-control')) !!}
                </div>            
            @endif
        @endif 
        </div>
        @if($user->usertype == "Super Admin")
            @if($isAdmin == true )
            <div class="form-group">
                <div class=" col-md-6">
                    {!! Form::label('email', 'Email' , array('class' => 'required')); !!}
                    {!! Form::text('email',null,array('class'=>'form-control','readonly'=>'true')) !!} 
                </div>
                
                <div class=" col-md-6">
                    {!! Form::label('phone_number', 'Phone Number'); !!}
                    {!! Form::text('phone_number',null,array('class'=>'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6">
                    {!! Form::label('update_password', 'Update Login Details' , array('class' => 'changePass')); !!}
                    {!! Form::select('update_password', array('No' => 'No', 'Yes' => 'Yes'), null, array('class' => 'form-control')) !!}
                </div>                
            </div>

            <div class="passwordFields">
                <div class="form-group">
                    <div class="col-md-6">
                        {!! Form::label('email', 'Update Email' , array('class' => 'required')); !!}
                        {!! Form::text('email',null,array('class'=>'form-control')) !!} 
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        {!! Form::label('password', 'Password' , array('class' => 'required answer')); !!}
                        {!! Form::text('password',null,array('class'=>'form-control')) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::label('confirmpassword', 'Confirm Password' , array('class' => 'required answer')); !!}
                        {!! Form::text('confirmpassword',null,array('class'=>'form-control')) !!}
                    </div>
                </div>
            </div> 

            <div class="form-group profileImg"> 
                <div class="col-md-12">
                    <?php
                    if(Auth::check())
                    {                        
                        $profileImage  = Auth::user()->profile_image;
                        $defaultPath = URL::to('/public/asset/User/Profile/thumb').'/'.'avatar.jpg';
                        if($profileImage && $profileImage !="")
                        {
                            $imgPath     = URL::to('/public/asset/User/Profile/thumb').'/'.$profileImage;
                            if (file_exists($imgPath)) 
                            {
                                $imgPath = $defaultPath;;
                            }
                            else
                            {
                                $imgPath = $imgPath;
                            } 
                        }
                        else
                        {
                            $imgPath = $imgPath; 
                        }                   
                    }
                    ?>
                    <img class="m-r-sm" alt="Admin" src="<?php echo $imgPath; ?>">                
                </div>
            </div>

            <div class="form-group profileImg">                               
                <div class="input_fields_wrap2 col-md-12">
                    <button class="add_field_button2 btn btn-primary">Change Image</button>
                </div>                          
            </div>
        @endif
    @endif       

    <div class="form-group">
        <div class="col-md-12">
            {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" href="{{ url('/admin/user')}}">Cancel</a>
        </div>
    </div>

  {!! Form::close() !!} 
</div>
@endsection
