@extends('layouts.admin')
@section('content')

<div class="panel-body viewPage">
    <div class="form-group">
        <label class="col-lg-3 control-label">Product Id</label>
        <div class="col-lg-9">
            <p><?php echo $prod->id; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Product Name</label>
        <div class="col-lg-9">
            <p><?php echo $prod->pname; ?></p>
        </div>
    </div>
    
    <div class="clear"></div>
    <?php $client = DB::table('brands')->lists('brand','id'); ?>
    <div class="form-group">
        <label class="col-lg-3 control-label">Product Brand</label>
        <div class="col-lg-9">
            <p><?php echo $client[$prod->brand]; ?></p>
        </div>
    </div>
    
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Product Year</label>
        <div class="col-lg-9">
            <p><?php echo $prod->year; ?></p>
        </div>
    </div>
     
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Status</label>
        <div class="col-lg-9">
            <p><?php echo $prod->status == 1 ? "Active" : "InActive"; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Created At</label>
        <div class="col-lg-9">
            <p><?php echo $prod->created_at; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Updated At</label>
        <div class="col-lg-9">
            <p><?php echo $prod->updated_at; ?></p>
        </div>
    </div>  
</div>
@endsection