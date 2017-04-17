@extends('layouts.admin')
@section('content')

<div class="panel-body viewPage">
    <div class="form-group">
        <label class="col-lg-2 control-label">Id</label>
        <div class="col-lg-10">
            <p><?php echo $page->id; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Customer Id</label>
        <div class="col-lg-10">
            <p"><?php echo $page->cust_id; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Seller Id</label>
        <div class="col-lg-10">
            <p><?php echo $page->seller_id; ?></p>
        </div>
    </div>
    
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Request Id</label>
        <div class="col-lg-10">
            <p><?php echo $page->request_id; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Is Customer Confirmed</label>
        <div class="col-lg-10">
            <p><?php echo $page->cust_confirmation; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Is Customer Confirmed</label>
        <div class="col-lg-10">
            <p><?php echo $page->seller_confirmation; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Created At</label>
        <div class="col-lg-10">
            <p><?php echo $page->created_at; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Updated At</label>
        <div class="col-lg-10">
            <p><?php echo $page->updated_at; ?></p>
        </div>
    </div>  
</div>
@endsection