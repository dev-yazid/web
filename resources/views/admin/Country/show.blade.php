@extends('layouts.admin')
@section('content')

<div class="panel-body viewPage">
    <div class="form-group">
        <label class="col-lg-3 control-label">Qualification Id</label>
        <div class="col-lg-9">
            <p><?php echo $qual->id; ?></p>
        </div>
    </div>
    
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Name</label>
        <div class="col-lg-9">
            <p><?php echo $qual->name; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Order</label>
        <div class="col-lg-9">
            <p><?php echo $qual->orderno; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Status</label>
        <div class="col-lg-9">
            <p><?php echo $qual->status == 0 ? "Active" : "InActive"; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Created At</label>
        <div class="col-lg-9">
            <p><?php echo $qual->created_at; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Updated At</label>
        <div class="col-lg-9">
            <p><?php echo $qual->updated_at; ?></p>
        </div>
    </div>  
</div>
@endsection