<nav class="navbar navbar-inverse navbar-default" role="navigation">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#"><?php echo lang('msg_site_name')?></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a href="<?php echo base_url().'admin/categories'?>"><?php echo lang('msg_categories')?></a></li>
				<li><a href="<?php echo base_url().'admin/products'?>"><?php echo lang('msg_products')?></a></li>
				<li><a href="<?php echo base_url().'admin/county'?>"><?php echo lang('msg_county')?></a></li>
				<li>
					<a href="<?php echo base_url().'admin/cities'?>"><?php echo lang('msg_city')?></a>
				</li>
				
<!-- 				<li>
	<a href="<?php echo base_url().'admin/adver'?>"><?php echo lang('msg_adver')?></a>
</li> -->

<li>
	<a href="<?php echo base_url().'admin/users'?>"><?php echo lang('msg_users')?></a>
</li>
<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo lang('msg_settings')?><b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><a href="<?php echo base_url().'admin/settings/currency'?>" class="red"><?php echo lang('msg_currency')?></a></li>
		<li><a href="<?php echo base_url().'admin/settings/mail'?>" class="red"><?php echo lang('msg_email')?></a></li>
		<!-- <li><a href="<?php //echo base_url().'admin/settings/rules' ?>" class="red"><?php //echo lang('msg_rules') ?></a></li> -->
	</ul>
</li>
</ul>
<?php
$CI =& get_instance();
$CI->load->view('backends/commons/userbox');
?>
</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>