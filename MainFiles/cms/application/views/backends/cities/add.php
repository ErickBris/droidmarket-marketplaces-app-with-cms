<?php
$CI =& get_instance();
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#name').focus();
});
</script>
<div class="container-fluid wrapper">
	<?php 
	if($CI->session->flashdata('msg_ok')){
		echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$CI->session->flashdata('msg_ok').'</div>';
	}
	?>
	<form class="form-horizontal" id="form" method="post">
		<fieldset>
			<legend>
				<?php echo lang('msg_add_cities'); ?>
			</legend>

			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('msg_name'); ?></label>
				<div class="col-xs-10">
					<input type="text" id="name" name="name" class="form-control" >
					<?php echo form_error('name'); ?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" for="txtName"><?php echo lang('msg_county'); ?></label>
				<div class="col-sm-10">
					<select id="county" name="county" class="form-control">
						<option value="">-----<?php echo lang('msg_county'); ?>----</option>
						<?php foreach($county as $r){?>
						<option value="<?php echo $r->id; ?>"><?php echo $r->name; ?></option>
						<?php }?>
					</select>
					<?php echo form_error('county'); ?>
				</div>
			</div>

			<div class="form-group">
				<div class="col-xs-10 col-xs-offset-2">
					<button type="submit" class="btn btn-primary" >
						<?php echo lang('msg_save'); ?>
					</button>
					<input type="reset" class="btn" value="<?php echo lang('msg_reset'); ?>"/>
				</div>
			</div>

		</fieldset>
	</form>
