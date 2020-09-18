@extends('admin.common.index')
@section('page_title')
  Edit User
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  	<div class="container-fluid">
	    <div class="row mb-2">
	      	<div class="col-sm-6">
	        	<h1>Edit User</h1>
	      	</div>
	      	<div class="col-sm-6">
	        	<ol class="breadcrumb float-sm-right">
	          		<li class="breadcrumb-item"><a href="{{route('users')}}">Users</a></li>
	          		<li class="breadcrumb-item active">Edit User</li>
	        	</ol>
	      	</div>
	    </div>
  	</div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
  	<div class="container-fluid">
  		@if(session()->has('error'))
		<div class="alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h5><i class="icon fas fa-ban"></i> ERROR!</h5>
			{{session()->get('error')}}
		</div>
		@elseif(session()->has('message'))
		<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h5><i class="icon fas fa-check"></i> SUCCESS!</h5>
			{{session()->get('message')}}
		</div>
		@endif
	    <div class="row">
	      	<!-- left column -->
	      	<div class="col-md-12">
		        <!-- jquery validation -->
		        <div class="card card-primary">
		          	<!-- /.card-header -->
		          	<!-- form start -->
		          	<form role="form" id="quickFormEdit" method="post" action="{{route('update_user', $user->id)}}">
		          		@CSRF
			            <div class="card-body">
			            	<div class="form-group">
				                <label for="exampleInputUserName">Username</label>
				                <input type="text" name="username" class="form-control" id="exampleInputUserName" placeholder="Enter First Name" value="{{ $user->username }}">
			              	</div>

			              	<div class="form-group">
				                <label for="exampleInputPhone">Phone</label>
				                <input type="tel" name="phone" class="form-control" id="exampleInputPhone" placeholder="Enter Last Name" value="{{ $user->phone }}">
			              	</div>

			              	<div class="form-group">
				                <label for="exampleInputEmail1">Email address</label>
				                <input type="email" name="email" class="form-control" id="exampleInputEmail" placeholder="Enter email" value="{{ $user->email }}">
			              	</div>

			              	<div class="form-group">
				                <label for="exampleInputPassword1">New Password (Optional)</label>
				                <input type="password" name="password" class="form-control" id="exampleInputPassword" placeholder="Password">
			              	</div>
		            	</div>
			            <!-- /.card-body -->
			            <div class="card-footer">
			              	<button type="submit" class="btn btn-primary">Submit</button>
			            </div>
		          	</form>
		        </div>
		        <!-- /.card -->
	        </div>
	      	<!--/.col (left) -->
	      	<!-- right column -->
	      	<div class="col-md-6">

	      	</div>
	      <!--/.col (right) -->
	    </div>
	    <!-- /.row -->
  	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection