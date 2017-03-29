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
        <label class="col-lg-2 control-label">User IP Address</label>
        <div class="col-lg-10">
            <p"><?php echo $page->ip; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Client Id</label>
        <div class="col-lg-10">
            <p><?php echo $page->cid; ?></p>
        </div>
    </div>
    
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Freelancer Id</label>
        <div class="col-lg-10">
            <p><?php echo $page->fid; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Project Id</label>
        <div class="col-lg-10">
            <p><?php echo $page->pid; ?></p>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Payment Status</label>
        <div class="col-lg-10">
            <p><?php echo $page->pay_status == 1 ? "Active":"InActive"; ?></p>
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