<div class="container-fluid wrapper">
	<div class="page-header controls-wrapper">
		<a href="<?php echo base_url().'admin/adver/create';?>" class="btn btn-primary"><?php echo lang('msg_add');?></a>
		<input class="search-query form-control col-sm-2" type="text" value=""  placeholder="<?php echo lang('msg_search');?>">
	</div>

	<script type="text/javascript">
	$('.search-query').keypress(function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) {
			var q = $('.search-query').val();
			if (q != "") {
				location.href ="<?php echo base_url().'admin/adver/search';?>?query=" + q;
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
						<th><?php echo lang('msg_banner'); ?></th>
						<th><?php echo lang('msg_position'); ?></th>
						<th><?php echo lang('msg_status'); ?></th>
						<th colspan="2"><?php echo lang('msg_operation');?></th>
					</tr>
				</thead>
				<tbody>
					<?php if($data['list']!=null)
					foreach($data['list'] as $r){?>
					<tr>
						<td><?php echo $r->id;?></td>
						<td>
							<img src="<?php echo base_url().$r->path;?>" width="300" height="200"/>
						</td>
						<td><?php 
						if($r->position==0){
							echo lang('msg_home');
						}
						if($r->position==1){
							echo lang('msg_categories');
						}
						if($r->position==2){
							echo lang('msg_single_post');
						}
						?></td>
						<td>
							<?php
							if($r->activated == ACTIVATED){
								echo '<span class="label label-success" >'.lang('msg_activate').'</span>';
							}else{
								echo '<span class="label label-danger">'.lang('msg_deactivate').'</span>';
							}
							;?>
						</td>
						<td><a class="btn btn-info"  href="<?php echo base_url().'admin/adver/edit?id='.$r->id;?>"><?php echo lang('msg_edit');?></a></td>
						<td><a class="btn btn-danger" href="<?php echo base_url().'admin/adver/delete?id='.$r->id;?>" onclick="return confirm('<?php echo $data['msg_label']['delete'];?>')"><?php echo lang('msg_delete');?></a></td>
					</tr>
					<?php }?>
				</tbody>
			</table>
			<?php echo $data['page_link'];?>
		</div>
	</div>
	<!--end row fluid-->

