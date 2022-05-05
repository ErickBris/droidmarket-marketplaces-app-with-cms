<!doctype html>
<html>
<head>
	<?php
	$CI =& get_instance();
	$CI->load->view('backends/commons/header');
	?>
</head>
<body>
	<div class="row">
		<div class="alert alert-danger">
			You have no right permision to access this page, go  <a href="<?php echo base_url().'admin/dashboard';?>">back</a> to admin panel or <a href="<?php echo base_url().'admin/dashboard/logout'?>">logout</a>
		</div>
	</div>
	<style type="text/css">
	.alert{
		text-align: center;
		margin-top: 10px;
		margin-left: 50px;
		margin-right: 20px;
	}
	</style>
</body>
</html>