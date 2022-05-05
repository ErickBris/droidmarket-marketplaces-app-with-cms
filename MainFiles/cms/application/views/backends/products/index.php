<div class="container-fluid wrapper">
	<div class="page-header controls-wrapper">
		<a href="<?php echo base_url().'admin/products/create'?>" class="btn btn-primary"><?php echo lang('msg_add')?></a>
		<input class="search-query form-control col-sm-2" type="text" value=""  placeholder="<?php echo lang('msg_search')?>">
	</div>

	<script type="text/javascript">
	$('.search-query').keypress(function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) {
			var q = $('.search-query').val();
			if (q != "") {
				location.href ="<?php echo base_url().'admin/products/search'?>?query=" + q;
			}
		}
	})
	</script>
	
	<!--show alert messager-->
	<h4>
		<?php if(isset($data['search_title']))
		echo $data['search_title'];
		?>
	</h4>
	<!--end show alert messager-->
	<div class="row-fluid wrapper">
		<div class="span12 ">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th><a href=""><?php echo lang('msg_id')?></a></th>
						<th><?php echo lang('msg_thumb')?></th>
						<th><?php echo lang('msg_title')?></th>
						<th><?php echo lang('msg_content')?></th>
						<th><?php echo lang('msg_price')?></th>
						<th><?php echo lang('msg_post_user')?></th>
						<th><?php echo lang('msg_status')?></th>
						<th><?php echo lang('msg_operation')?></th>
					</tr>
				</thead>
				<tbody>
					<?php if($data['list']!=null)
					foreach($data['list'] as $r){

						?>
						<tr>
							<td><?php echo $r->id?></td>
							<?php
							if($r->image_path!=null){
								?>
								<td><img class="thumbnail" src='<?php echo base_url().$r->image_path;?>' alt="<?php echo $r->title;?>"/></td>
								<?php }else{ ?>
								<td><img class="thumbnail" src='<?php echo base_url()."statics/images/no_photo.png";?>' alt="<?php echo $r->title;?>"/></td>
								<?php } ?>
								<td><?php echo $r->title; ?></td>
								<td><?php echo $r->content; ?></td>
								<td><?php echo $r->price; ?></td>
								<td><?php echo $r->email; ?></td>
								<td><?php
								if($r->activated == 1){
									echo '<span class="label label-success" >'.lang('msg_activate').'</span>';
								}else{
									echo '<span class="label label-danger">'.lang('msg_deactivate').'</span>';
								}
								?></td>
								<td>
									<div class="btn-group">
										<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
											<?php echo lang('msg_operation');?>
											<span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
											<li><a href="<?php echo base_url().'index.php/admin/products/lock?id='.$r->id ;?>" ><?php echo lang('msg_deactivate');?></a></li>
											<li><a href="<?php echo base_url().'index.php/admin/products/activate?id='.$r->id ; ?>"><?php echo lang('msg_activate');?></a></li>
											<li><a href="<?php echo base_url().'index.php/admin/products/delete?id='.$r->id ;?>" onclick="return confirm('<?php echo lang('msg_confirm_delete')?>')"><?php echo lang('msg_delete');?></a></li>
										</ul>
									</div>
								</td>

							</tr>
							<?php
							
						}
						?>
					</tbody>
				</table>
				<?php echo $data['page_link'];?>
			</div>

