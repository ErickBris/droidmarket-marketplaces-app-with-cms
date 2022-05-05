<!doctype html>
<html>
<head>
	<?php 
	$CI =& get_instance();
	$CI->load->view('backends/commons/header');
	?>
	<style type="text/css">
	.navbar{
		border-radius: 0px;
		-moz-border-radius:0px;
	}
	
	.controls-wrapper .search-query{
		float: right;
		width: 30%;
	}

	.controls-wrapper{
		margin-top: 0px;
	}

	.thumbnail{
		width: 100px;
		height: 100px;
	}
	</style>
</head>
<body>
	<?php
	$CI->load->view('backends/commons/topmenu');
	?>

    <?php
    	echo $content;
    ?>

	<?php
	$CI->load->view('backends/commons/footer');
	?>
</body>
</html>