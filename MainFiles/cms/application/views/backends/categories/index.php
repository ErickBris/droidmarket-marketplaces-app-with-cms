<div class="container-fluid wrapper">
	<div class="page-header controls-wrapper">
		<a href="<?php echo base_url().'admin/categories/create';?>" class="btn btn-primary"><?php echo lang('msg_add');?></a>
		<input class="search-query form-control col-sm-2" type="text" value=""  placeholder="<?php echo lang('msg_search');?>">
	</div>

	<script type="text/javascript">
	$('.search-query').keypress(function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) {
			var q = $('.search-query').val();
			if (q != "") {
				location.href ="<?php echo base_url().'admin/categories/search';?>?query=" + q;
			}
		}
	})
	</script>
	
	<!--show alert messager-->
	<h4>
		<?php if(isset($data['search_title'])){ ?>
		<?php echo $data['search_title'];?>
		<?php };?>
	</h4>
	<!--end show alert messager-->
	<div class="row-fluid wrapper">
		<div class="span12 ">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th><a href=""><?php echo lang('msg_id');?></a></th>
						<th><?php echo lang('msg_name');?></th>
						<th><?php echo lang('msg_parent_categories');?></th>
						<th colspan="2"><?php echo lang('msg_operation');?></th>
					</tr>
				</thead>
				<tbody>
					<?php if($data['list']!=null)
					foreach($data['list'] as $r){?>
					<tr>
						<td><?php echo $r->id;?></td>
						<td><?php echo $r->name;?></td>
						<td>
							<?php
							if($r->parent_id != 0){
								echo '<span class="label label-success" >'.lang('msg_no').'</span>';
							}else{
								echo '<span class="label label-danger">'.lang('msg_yes').'</span>';
							}
							;?>
						</td>
						<td><a class="btn btn-info"  href="<?php echo base_url().'admin/categories/edit_get?id='.$r->id;?>"><?php echo lang('msg_edit');?></a></td>
						<td><a class="btn btn-danger" href="<?php echo base_url().'admin/categories/delete?id='.$r->id;?>" onclick="return confirm('<?php echo $data['msg_label']['delete'];?>')"><?php echo lang('msg_delete');?></a></td>
					</tr>
					<?php }?>
				</tbody>
			</table>
			<?php echo $data['page_link'];?>
		</div>
	</div>
	<!--end row fluid-->

