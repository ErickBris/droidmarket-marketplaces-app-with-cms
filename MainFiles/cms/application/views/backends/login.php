<!doctype html>
<html>
<head>
	<?php 
	$CI =& get_instance();
	$CI->load->view('backends/commons/header');
	?>
	<style type="text/css">
	body {
		padding-top: 40px;
		padding-bottom: 40px;
	}

	.wrapper {
		margin-top: 10px;
	}

	.btn-group .search-query {
		border-radius: 0px;
		-moz-border-radius: 0px;
		-webkit-border-radius: 0px;
		-chrome-border-radius: 0px;
	}

	.input-append {
		margin-bottom: 0px
	}

	.input-append .option-search {
		width: 150px;
	}

	.control-group input[type="text"], 
	.control-group input[type="password"],
	select{
		width: 300px;
	}

	textarea{
		height: 400px;
		width: 500px;
	}

	.control-group select{
		width: 315px;
	}

	.error-line{
		color:#B94A48
	};

	</style>
</head>
<body>
	<div class="container">
		<form class="form-login" method="post" action="<?php echo base_url().'admin/dashboard/login'?>">

			<div class="form-group">
				<h3 class="form-login-heading">DroidMarket CMS</h3>
				<input name="user_name" id="user_name" type="text" class="input-block-level" placeholder="Username" size="50" >
				<?php echo form_error('user_name')?>
			</div>

			<div class="form-group">
				<!-- password -->
				<input name="pwd" id="pwd" type="password" class="input-block-level" placeholder="Password" size="50">
				<?php echo form_error('pwd')?>
			</div>
			<!-- remember me -->
			<span class="error"><?php if(isset($error_msg)) {echo $error_msg;} ?></span>
		<button class="btn btn-success" type="submit">Login</button>
	</form>
</div>
<!--end container -->
</body>
</html>