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
            <p><?php echo $brodRequests->name.' [ '.$brodRequests->year.' ]'; ?></p>
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
                <?php $status = $brodRequests->status; 

                if($status == 0)
                {
                    echo "New Request";
                }
                else if($status == 1)
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
    <div class="form-group" style="margin-top: 20px;">   
        <table class="table table-condensed" >
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Price (SAR)</th>
                    <th>Seller Name</th>
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
                    <td>
                    <?php $status = $response->brodStatus; 
                        if($status == 0)
                        {
                            echo "In Progress";
                        }
                        else if($status == 1)
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