@extends('layouts.admin')
@section('content')
<div class="panel-body">
	<div class="form-group">
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<a href="{{ url('/admin/user')}}">
					<span class="info-box-icon bg-aqua">
						<i class="fa fa-users" aria-hidden="true"></i>
					</span>
				</a>
				<div class="info-box-content">
				<span class="info-box-text">Users</span>
					<span class="info-box-number"><?php echo $totalUsers; ?></span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">				
				<a href="{{ url('/admin/product')}}">
					<span class="info-box-icon bg-red">
						<i class="fa fa-file-text icon"></i>
					</span>
				</a>				
				<div class="info-box-content">
					<span class="info-box-text">Products</span>
					<span class="info-box-number"><?php echo $totalProduct; ?></span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">				
				<a href="{{ url('/admin/brand')}}">
					<span class="info-box-icon bg-yellow">
						<i class="fa fa-bold" aria-hidden="true"></i>
					</span>
				</a>
				<div class="info-box-content">
					<span class="info-box-text">Brands</span>
					<span class="info-box-number"><?php echo $totalBrand; ?></span>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">				
				<a href="{{ url('/admin/request')}}">
					<span class="info-box-icon bg-green">
						<i class="fa fa-tasks icon"></i>
					</span>
				</a>				
				<div class="info-box-content">
					<span class="info-box-text">Total Request</span>
					<span class="info-box-number"><?php echo $totalBrodRequest; ?></span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">				
				<a href="{{ url('/admin/response')}}">
					<span class="info-box-icon bg-blue">
						<i class="fa fa-tasks icon"></i>
					</span>
				</a>				
				<div class="info-box-content">
					<span class="info-box-text">Total Response</span>
					<span class="info-box-number"><?php echo $totalBrodResponse; ?></span>
				</div>
			</div>
		</div>
	
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">				
				<a href="{{ url('/admin/transaction')}}">
					<span class="info-box-icon bg-navy">
						<i class="fa fa-money"></i>
					</span>
				</a>
				<div class="info-box-content">
					<span class="info-box-text">Transactions</span>
					<span class="info-box-number"><?php echo $totalTransactions; ?></span>
				</div>
			</div>
		</div>		
	</div>
</div>

@endsection


