<div class="container-fluid wrapper">
	<!--show alert messager-->
	<?php 
	$CI =& get_instance();
	if($CI->session->flashdata('msg_ok')){
		echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$CI->session->flashdata('msg_ok').'</div>';
	}

	?>
	<!--end show alert messager-->
	<form class="form-horizontal" id="form" method="post" action="" enctype="multipart/form-data">
		<fieldset>
			<legend>
				<?php echo lang('msg_update_profile');?>
			</legend>
			<input type="hidden" name="id" id="id" value="<?php echo $obj[0]->id;?>">

			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('msg_full_name');?></label>
				<div class="col-xs-10">
					<input type="text" id="full_name" class="form-control" name="full_name"  value="<?php echo $obj[0]->full_name;?>">
					<?php echo form_error('full_name');?>
				</div>
			</div>


			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('msg_email');?></label>
				<div class="col-xs-10">
					<input type="text" id="email" class="form-control" name="email" value="<?php echo $obj[0]->email;?>">
					<?php echo form_error('email');?>
				</div>
			</div>


			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('msg_address');?></label>
				<div class="col-xs-10">
					<input type="text" id="address" class="form-control" name="address" value="<?php echo $obj[0]->address;?>">
					<?php echo form_error('address');?>
				</div>
			</div>

			<div class="form-group">
				<div class="col-xs-10 col-xs-offset-2">
					<button type="submit" class="btn btn-primary" >
						<?php echo lang('msg_save');?>
					</button>
				</div>
			</div>
		</fieldset>
	</form>

