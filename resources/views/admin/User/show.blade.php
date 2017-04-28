@extends('layouts.admin')
@section('content')
<div class="panel-body viewPage">
    <div class="form-group"> 
        <h4 class="col-lg-12 control-label">User Details</h4>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">User Id</label>
        <div class="col-lg-9">
            <p><?php echo $user->userId; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Full Name</label>
        <div class="col-lg-9">
            <p><?php echo $user->fullname ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Email</label>
        <div class="col-lg-9">
            <p>
                <a href="mailto:<?php echo $user->email; ?>">
                    <?php echo $user->email; ?>
                </a>
                <?php echo $user->email_verified == "Yes" ? "[ Verified ]" : "[ Not Verified ]" ?>
            </p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Is Customer Updated</label>
        <div class="col-lg-9">
            <p><?php echo $user->is_customer_updated == 1 ? "Yes" : "No" ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Is Mobile Verified</label>
        <div class="col-lg-9">
            <p><?php echo $user->mobile_verified == 1 ? "Yes" : "No" ?></p>
        </div>
    </div>

    <?php if($user->is_seller_updated == 1) { ?>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Is Seller Updated</label>
        <div class="col-lg-9">
            <p><?php echo $user->is_seller_updated == 1 ? "Yes" : "No" ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Is Mobile Verified</label>
        <div class="col-lg-9">
            <p><?php echo $user->seller_mobile_verified == 1 ? "Yes" : "No" ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Seller Name</label>
        <div class="col-lg-9">
            <p><?php echo $user->seller_name == 1; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Shop Name</label>
        <div class="col-lg-9">
            <p><?php echo $user->shop_name == 1; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Shop City</label>
        <div class="col-lg-9">
            <p><?php echo $user->shop_address; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Shop City</label>
        <div class="col-lg-9">
            <p><?php echo $user->shop_city_name; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Shop Zipcode</label>
        <div class="col-lg-9">
            <p><?php echo $user->shop_zipcode; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Shop Mobile</label>
        <div class="col-lg-9">
            <p><?php echo $user->shop_mobile; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Shop Start Time</label>
        <div class="col-lg-9">
            <p><?php echo $user->shop_start_time; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Shop Close Time</label>
        <div class="col-lg-9">
            <p><?php echo $user->shop_close_time; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Shop Location</label>
        <div class="col-lg-9">
            <p><a href="https://www.google.com/maps?q={{trim($user->shop_location_map)}}" target="_blank">
                <?php echo 'View Location'; ?></a>
            </p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Seller Status</label>
        <div class="col-lg-9">
            <p><?php echo $user->status == 1 ? "Active" : "InActive"; ?></p>
        </div>
    </div> 

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Shop Licence</label>
        <div class="col-lg-9">
            <p>
                <a href="{{ asset('public/asset/shopLicence/thumb/'.$user->shop_document) }}" target="_blank">
                    <?php //echo $user->shop_document; 
                    echo 'View Licence'; ?>
                </a>
            </p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">User Status</label>
        <div class="col-lg-9">
            <div class="col-lg-3">       
                {{ Form::open(array('url' => 'admin/user/statusChange','class'=>"form-horizontal")) }}
                <div class="form-group">
                   
                        {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), $user->status, array('class' => 'form-control')) !!}
                    </div> 
                    <?php echo Form::hidden('id', $user->userId); ?>
                </div>      
            </div>

            {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" href="{{ url('/admin/user')}}">Cancel</a>

            {!! Form::close() !!}
            
        </div>
    </div>
    <?php } ?>
</div>
@endsection