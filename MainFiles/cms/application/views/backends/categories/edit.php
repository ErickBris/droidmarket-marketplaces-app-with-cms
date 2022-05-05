<?php
$CI =& get_instance();
;?>
<div class="container-fluid wrapper">
	<?php 
	if($CI->session->flashdata('msg_ok')){
		echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$CI->session->flashdata('msg_ok').'</div>';
	}
	;?>
	<form class="form-horizontal" id="form" method="post" action="<?php echo base_url().'admin/categories/edit_post';?>">
		<fieldset>
			<legend>
				<?php echo lang('msg_edit_categories');?>
			</legend>

			<input type="hidden" name="id" id="id" value="<?php echo $obj[0]->id;?>">
			<div class="form-group">
				<label class="control-label col-sm-2" for="txtName"><?php echo lang('msg_name');?></label>
				<div class="controls col-sm-10">
					<input type="text" id="name" name="name" value="<?php echo $obj[0]->name;?>" class="form-control">
					<?php echo form_error('name');?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" for="txtName"><?php echo lang('msg_parent_categories');?></label>
				<div class="col-sm-10">
					<select id="parent_id" name="parent_id" class="form-control">
						<option value="0">-----<?php echo lang('msg_parent_categories');?>----</option>
						<?php foreach($parent_cat as $r){?>
						<option value="<?php echo $r->id;?>" <?php if($r->id==$obj[0]->parent_id){
							echo 'selected';
						} ?>><?php echo $r->name;?></option>
						<?php };?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<button type="submit" class="btn btn-primary" >
						<?php echo lang('msg_save');?>
					</button>
				</div>
			</div>
		</fieldset>
	</form>