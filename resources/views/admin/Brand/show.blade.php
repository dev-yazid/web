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
        <label class="col-lg-2 control-label">Title</label>
        <div class="col-lg-10">
            <p><?php echo $page->brand; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Image</label>
        <div class="col-lg-10"><img class="col-mg-2" src="<?php echo asset('public/asset/brand/'.$page->image) ?>">
        </div>
    </div>      
</div>
@endsection