<div class="container-fluid wrapper">

	<!--show alert messager-->

	<!--end show alert messager-->

	<form class="form-horizontal" id="form" method="post" action="" enctype="multipart/form-data">
		<fieldset>
			<legend>
				<?php echo lang('msg_settings');?>&nbsp;-&nbsp;<?php echo lang('msg_email');?>
			</legend>

			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('smtp_host');?></label>
				<div class="controls col-xs-10">
					<input type="text" id="host" class="form-control" name="host" value="<?php echo $obj['smtp_host'];?>">
					<?php echo form_error('host');?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('smtp_user');?></label>
				<div class="controls col-xs-10">
					<input type="text" id="user" class="form-control" name="user" value="<?php echo $obj['smtp_user'];?>">
					<?php echo form_error('user');?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('smtp_pwd');?></label>
				<div class="controls col-xs-10">
					<input type="password" id="pwd" class="form-control" name="pwd" value="<?php echo $obj['smtp_pass'];?>">
					<?php echo form_error('pwd');?>
				</div>
			</div>


			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('smtp_port');?></label>
				<div class="controls col-xs-10">
					<input type="text" id="port" class="form-control" name="port" value="<?php echo $obj['smtp_port'];?>">
					<?php echo form_error('port');?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('mailpath');?></label>
				<div class="controls col-xs-10">
					<input type="text" id="mail_path" class="form-control" name="mail_path" value="<?php echo $obj['mailpath'];?>">
					<?php echo form_error('port');?>
				</div>
			</div>


			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('from_user');?></label>
				<div class="controls col-xs-10">
					<input type="text" id="from_user" class="form-control" name="from_user" value="<?php echo $obj['from_user'];?>">
					<?php echo form_error('from_name');?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('from_email');?></label>
				<div class="controls col-xs-10">
					<input type="text" id="from_email" class="form-control" name="from_email" value="<?php echo $obj['from_email'];?>">
					<?php echo form_error('from_email');?>
				</div>
			</div>

			<div class="form-group">
				<div class="col-xs-10 col-xs-offset-2">
					<button type="submit" class="btn btn-primary" >
						<?php echo lang('msg_save');?>
					</button>
					<a href="<?php echo base_url();?>admin/settings/reset_mail_settings" class="btn btn-default">
						<?php echo lang('reset_default');?>
					</a>
				</div>
			</div>

		</fieldset>
	</form>

