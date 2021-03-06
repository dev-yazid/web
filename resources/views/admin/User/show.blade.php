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
            <p><?php echo $user->name ?></p>
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
        <label class="col-lg-3 control-label">User Type</label>
        <div class="col-lg-9">
            <p><?php echo $user->usertype ?></p>
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
        <label class="col-lg-3 control-label">Is Seller Updated</label>
        <div class="col-lg-9">
            <p><?php echo $user->is_seller_updated == 1 ? "Yes" : "No" ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">User Status</label>
        <div class="col-lg-9">
            <p><?php echo $user->status == 1 ? "Active" : "InActive"; ?></p>
        </div>
    </div>    

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Registered On</label>
        <div class="col-lg-9">
            <p><?php echo $user->created_at; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Updated On</label>
        <div class="col-lg-9">
            <p><?php echo $user->updated_at; ?></p>
        </div>
    </div>

    <?php if (($user->usertype == 'Seller' || $user->usertype == 'Both' ) && $user->is_seller_updated == 1) { ?>
        <div class="form-group"> 
            <h4 class="col-lg-12 control-label">Shop Details</h4>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label">Shop Name</label>
            <div class="col-lg-9">
                <p><?php echo $user->shop_name; ?></p>
            </div>
        </div>
        <div class="clear"></div>

        <div class="form-group">
            <label class="col-lg-3 control-label">Mobile</label>
            <div class="col-lg-9">
                <p><?php echo $user->shop_mobile; ?></p>
            </div>
        </div>
        <div class="clear"></div>

        <div class="form-group">
            <label class="col-lg-3 control-label">Shop Address</label>
            <div class="col-lg-9">
                <p>
                    <?php echo $user->shop_address; ?> |
                    <?php echo $user->shopcity->name; ?> |
                    <?php echo $user->shop_zipcode; ?> |
                    <?php echo "Soudi Arabia"; ?>
                </p>
            </div>
        </div>
        <div class="clear"></div>

        <div class="form-group">
            <label class="col-lg-3 control-label">Shop Timings</label>
            <div class="col-lg-9">
                <p><?php echo $user->shop_start_time; ?> to <?php echo $user->shop_close_time; ?></p>
            </div>
        </div>
        <div class="clear"></div>

        <div class="form-group">
            <label class="col-lg-3 control-label">Shop Document</label>
            <div class="col-lg-9">
                <p><a href="{{ asset("public/asset/licence/".$user->shop_document) }}" target="blank">                 
                        <?php //echo $user->shop_document; 
                        echo 'View Document'; ?></a>
                </p>
            </div>
        </div>
        
        <div class="clear"></div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Shop Location Map</label>
            <div class="col-lg-9">
                <p><a href="{{ $user->shop_location_map }}" target="blank">                 
                        <?php echo 'View Map'; ?></a>
                </p>
            </div>
        </div>
<?php } ?>

    <?php if (($user->usertype == 'Customer' || $user->usertype == 'Both') && $user->is_customer_updated == 1) { ?>

        <div class="form-group"> 
            <h4 class="col-lg-12 control-label">Customer Address</h4>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label">Customer Mobile</label>
            <div class="col-lg-9">
                <p><?php echo $user->phone_number; ?></p>
            </div>
        </div>
        <div class="clear"></div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Customer Address</label>
            <div class="col-lg-9">
                <p>
    <?php echo $user->customer_address; ?> |
    <?php echo $user->customercity->name; ?> |
                    <?php echo $user->customer_zipcode; ?> |
                    <?php echo "Soudi Arabia"; ?>

                </p>
            </div>
        </div>
<?php } ?>  
</div>
@endsection