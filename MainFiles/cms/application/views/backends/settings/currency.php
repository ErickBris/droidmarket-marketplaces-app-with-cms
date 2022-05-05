<?php
$CI =& get_instance();
?>
<div class="container wrapper">
	<?php 
	if($CI->session->flashdata('msg_ok')){
		echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$CI->session->flashdata('msg_ok').'</div>';
	}
	?>
	<form class="form-horizontal" id="form" method="post" action="" enctype="multipart/form-data">
		<fieldset>
			<legend>
				<?php echo lang('msg_settings');?>&nbsp;-&nbsp;<?php echo lang('msg_currency');?>
			</legend>
			<input type="hidden" name="id"/>
			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('msg_currency');?></label>
				<div class="col-xs-10">
					<select name="currency" class="form-control">
						<?php if($list!=null)
						foreach($list as $r){
							?>
							<option value="<?php echo $r->id;?>" <?php if($r->id==$obj['currency_id']){echo 'selected';} ?> ><?php echo $r->currency_code;?>&nbsp;(<?php echo $r->name;?>)</option>
							<?php }?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-xs-2" for="txtName"><?php echo lang('msg_currency_position');?></label>
					<div class="col-xs-10">
						<div class="checkbox">
							<label>
								<input type="radio" <?php if($obj['position']==CURRENCY_SYMBOL_BEFORE){echo 'checked';} ?>  class="position" name="position" value="<?php echo CURRENCY_SYMBOL_BEFORE?>"> <?php echo lang('msg_before'); ?>
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="radio"  <?php if($obj['position']==CURRENCY_SYMBOL_AFTER){echo 'checked';} ?>   class="position" name="position" value="<?php echo CURRENCY_SYMBOL_AFTER?>"> <?php echo lang('msg_after'); ?>
							</label>
						</div>
					</div>
				</div>


				<div class="form-group">
					<div class="col-xs-10 col-xs-offset-2">
						<button type="submit" class="btn btn-primary">
							<?php echo lang('msg_save');?>
						</button>
						<a href="<?php echo base_url();?>admin/settings/reset_currency_settings" class="btn btn-default">
							<?php echo lang('reset_default');?>
						</a>
					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>