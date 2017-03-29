@extends('layouts.admin')
@section('content')

<div class="panel-body viewPage">
    <div class="form-group">
        <label class="col-lg-3 control-label">Page Id</label>
        <div class="col-lg-9">
            <p class="form-control-static"><?php echo $page->id; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Language</label>
        <div class="col-lg-9">
            <p class="form-control-static"><?php echo $page->lang_code == "de" ? "German [de]" : "English"; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">English Label</label>
        <div class="col-lg-9">
            <p class="form-control-static"><?php echo $page->label; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">German Label</label>
        <div class="col-lg-9">
        <p class="form-control-static"><?php echo $page->changed_label == "" ? "Empty" : $page->changed_label ; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Page Url / Title</label>
        <div class="col-lg-9">
            <p class="form-control-static"><?php echo $page->page_url == "" ? "Empty" : $page->page_url; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Comments</label>
        <div class="col-lg-9">
            <p class="form-control-static"><?php echo $page->page_comments == "" ? "Empty" : $page->page_comments; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Created At</label>
        <div class="col-lg-9">
            <p class="form-control-static"><?php echo $page->created_at; ?></p>
        </div>
    </div>
    <div class="clear"></div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Updated At</label>
        <div class="col-lg-9">
            <p class="form-control-static"><?php echo $page->created_at; ?></p>
        </div>
    </div>   
</div>
@endsection