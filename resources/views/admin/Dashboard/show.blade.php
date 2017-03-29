@extends('layouts.admin')
@section('content')

<div class="form-horizontal">
    <div class="form-group">
        <label class="col-lg-2 control-label">Id :</label>
        <div class="col-lg-10">
            <p class="form-control-static"><?php echo $page->id; ?></p>
        </div>
    </div>
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Page Title :</label>
        <div class="col-lg-10">
            <p class="form-control-static"><?php echo $page->title; ?></p>
        </div>
    </div>
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Page Description :</label>
        <div class="col-lg-10">
            <p class="form-control-static"><?php echo $page->description; ?></p>
        </div>
    </div>   
</div>
@endsection