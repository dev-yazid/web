@if(Session::has('error_msg'))
<div class="alert alert-danger">
	<i class="fa fa-exclamation" aria-hidden="true"></i>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fa fa-ban-circle"></i> {{Session::get('error_msg')}}
</div>
@endif

@if(Session::has('alert_msg'))
<div class="alert alert-notify">
	<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fa fa-ok-sign"></i> {{Session::get('alert_msg')}}
</div>
@endif
 
@if(Session::has('success_msg'))
<div class="alert alert-success">
	<i class="fa fa-check" aria-hidden="true"></i>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fa fa-ok-sign"></i> {{Session::get('success_msg')}}
</div>
@endif
<?php /* Hide alert div after 7 sec using by jQuery in comman.js*/?>