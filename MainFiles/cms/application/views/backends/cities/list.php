	<option value="0">-----<?php echo lang('msg_city');?>------</option>
	<?php if($list!=null)
	foreach($list as $r){?>
	<option value="<?php echo $r->id ?>"><?php echo $r->name ?></option>
	<?php
}
?>