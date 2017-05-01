@extends('layouts.admin')
@section('content')
<div class="panel-body viewPage">
    <div class="form-group">
        <label class="col-lg-2 control-label">Id</label>
        <div class="col-lg-10">
            <p><?php echo $brodRequests->id; ?></p>
        </div>
    </div> 
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Title</label>
        <div class="col-lg-10">
            <p><?php echo $brodRequests->pname ?></p>
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
            <p><?php echo $brodRequests->pname; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    
    <div class="form-group">
    <label class="col-lg-2 control-label">Status</label>
        <div class="col-lg-10">
            <p>
                <?php $status = $brodRequests->status; 

                if($status == 1)
                {
                    echo "New Request";
                }
                else if($status == 2)
                {
                    echo "In Progress";
                }
                else
                {
                    echo "Compleate";
                }
                ?>
            </p>
        </div>
    </div>
    
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Requested Image</label>
        <div class="col-lg-10">
            <a target="_blank" href="<?php echo asset('public/asset/brodcastImg/thumb/'.$brodRequests->req_image) ?>"> 
                <img class="margin padding col-mg-2" alt="<?php echo $brodRequests->req_image; ?>" src="<?php echo asset('public/asset/brodcastImg/thumb/'.$brodRequests->req_image) ?>" height="100" width="100">
            </a>
        </div>
    </div>
    <div class="clear"></div>

    <div class="form-group" style="margin-top: 20px;">   
        <table class="table table-condensed" >
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Price (SAR)</th>
                    <th>Seller Name</th>
                    <th>Is Confirmed</th>
                    <th>Status</th>
                    <th>Response Date</th>
                    <th>Update Date</th>
                </tr>                
            </thead>
            <tbody>                
                <?php
                $responseCount = count($brodResponse);
                foreach ($brodResponse as $response) { ?>
                <tr >
                    <td><?php echo $response->id; ?></td>
                    <td><?php echo $response->price; ?></td>
                    <td><?php echo $response->name; ?></td>
                    <td><?php echo $response->is_prod_confirm_by_buyer == 1 ? "Yes" : "No" ?></td>
                    <td>
                    <?php $status = $response->brodStatus; 
                        if($status == 1)
                        {
                            echo "In Progress";
                        }
                        else if($status == 2)
                        {
                            echo "Decline";
                        }
                        else
                        {
                            echo "Compleate";
                        }
                        ?>                            
                    </td>
                    <td><?php echo $response->created_at; ?></td>
                    <td><?php echo $response->updated_at; ?></td>
                </tr>
                <?php
                }
                ?>                
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="
                    54" align="center"><strong>Total <?php echo $responseCount; ?> Seller Responded.</strong></td>                    
                </tr>
            </tfoot>
        </table>
   
    </div>  
</div>
@endsection