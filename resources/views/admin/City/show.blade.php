@extends('layouts.admin')
@section('content')

<div class="panel-body viewPage">
    <div class="form-group">
        <label class="col-lg-3 control-label">City Id</label>
        <div class="col-lg-9">
            <p><?php echo $city->id; ?></p>
        </div>
    </div>  
    
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Country</label>
        <div class="col-lg-9">
            <p><?php echo $country->name; ?> [ <?php echo $country->sortname; ?> ]</p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">State</label>
        <div class="col-lg-9">
            <p><?php echo $state->name; ?></p>
        </div>
    </div> 

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">City</label>
        <div class="col-lg-9">
            <p><strong><?php echo $city->name; ?></strong></p>
        </div>
    </div>   

    <div class="form-group">
        <label class="col-lg-3 control-label">Country Id</label>
        <div class="col-lg-9">
            <p><?php echo $country->id; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">City Status</label>
        <div class="col-lg-9">
            <p><?php echo $city->status == 1 ? "Active" : "InActive"; ?></p>
        </div>
    </div>
</div>
@endsection