<?php
$CI =& get_instance();
?>
<div class="container-fluid wrapper">
	<?php 
	if($CI->session->flashdata('msg_ok')){
		echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$CI->session->flashdata('msg_ok').'</div>';
	}
	?>
	<form class="form-horizontal" id="form" method="post" action="<?php echo base_url().'admin/county/edit_post';?>">
		<fieldset>
			<legend>
				<?php echo lang('msg_edit_county');?>
			</legend>

			<input type="hidden" name="id" id="id" value="<?php echo $data['obj'][0]->id;?>">
			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('msg_name');?></label>
				<div class="col-xs-10">
					<input type="text" id="name" name="name" class="form-control" value="<?php echo $data['obj'][0]->name;?>">
					<?php echo form_error('name');?>
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