@extends('layouts.admin')
@section('content')
<div class="panel-body viewPage">
    <div class="form-group">
    <div class=" col-md-12">
            <h4>Request Details</h4>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Id</label>
        <div class="col-lg-10">
            <p><?php echo $brodRequests->id; ?></p>
        </div>
    </div> 
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">User Name</label>
        <div class="col-lg-10">
            <p><?php echo $brodRequests->name; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Description</label>
        <div class="col-lg-10">
            <p><?php echo $brodRequests->description;  ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Brand Name</label>
        <div class="col-lg-10">
            <p><?php echo $brodRequests->brand;  ?></p>
        </div>
    </div> 
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Product Name</label>
        <div class="col-lg-10">
            <p><?php echo $brodRequests->pname.' [ '.$brodRequests->year.' ]'; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Request Image</label>
        <div class="col-lg-10">
            <p><?php echo $brodRequests->req_image; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Status</label>
        <div class="col-lg-10">
            <p>
                <?php 
                $respStatus = $brodRequests->status; 
                if($respStatus == 0)
                {
                    echo "New Request";
                }
                else if($respStatus == 1)
                {
                    echo "In Processing";
                }
                else
                {
                    echo "Decline";
                }
                ?>
            </p>
        </div>
    </div>    
    <div class="clear"></div>
    <div class="form-group">
        <div class=" col-md-12">
            <h4>Response Details</h4>
        </div>
    </div>
    <?php // print_r($brodResponse); die;?>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Id</label>
        <div class="col-lg-10">
            <p><?php echo $brodResponse->id; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Seller NAme</label>
        <div class="col-lg-10">
            <p><?php echo $brodResponse->name; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Response Status</label>
        <div class="col-lg-10">
            <p>
                <?php 
                $respStatus = $brodResponse->status; 
                if($respStatus == 1)
                {
                    echo "Compleate";
                }
                else if($respStatus == 0)
                {
                    echo "In Processing";
                }
                else
                {
                    echo "Decline";
                }
                ?>
            </p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Response Price</label>
        <div class="col-lg-10">
            <p><?php echo $brodResponse->price; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Response Price</label>
        <div class="col-lg-10">
            <p><?php echo $brodResponse->email; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Response Price</label>
        <div class="col-lg-10">
            <p><?php echo $brodResponse->price_updated ? "Yes" : "No"; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Seller Mobile</label>
        <div class="col-lg-10">
            <p><?php echo $brodResponse->phone_number; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Responsed On</label>
        <div class="col-lg-10">
            <p><?php echo $brodResponse->created_at; ?></p>
        </div>
    </div>
     <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Response Updated</label>
        <div class="col-lg-10">
            <p><?php echo $brodResponse->updated_at; ?></p>
        </div>
    </div> 
</div>
@endsection