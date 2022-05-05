<?php
$CI =& get_instance();
?>
<script type="text/javascript" src="<?php echo base_url()?>statics/tinymce/jquery.tinymce.js"></script>

<div class="container-fluid wrapper">
	<div>
		<?php 
		if($CI->session->flashdata('msg_ok')){
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$CI->session->flashdata('msg_ok').'</div>';
		}
		?>
	</div>

	<form class="form-horizontal" id="form" method="post" action="" enctype="multipart/form-data">
		<fieldset>
			<legend>
				<?php echo lang('msg_add_products')?>
			</legend>

			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo lang('msg_title');?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="title" name="title" value="<?php echo set_value('title')?>">
					<?php echo form_error('title');?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" for="txtName"><?php echo lang('msg_price')?></label>
				<div class="col-sm-10">
					<input type="text" id="price" class="form-control" name="price" value="<?php echo set_value('price')?>">
					<?php echo form_error('price')?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" for="txtName"><?php echo lang('msg_categories')?></label>
				<div class="col-sm-10">
					<select id="categories" name="categories" class="form-control">
						<option value="">-----<?php echo lang('msg_categories')?>----</option>
						<?php foreach($data['categories'] as $r){?>
						<option value="<?php echo $r->id?>"><?php echo $r->name?></option>
						<?php }?>
					</select>
					<?php echo form_error('categories')?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" for="txtName"><?php echo lang('msg_county')?></label>
				<div class="col-sm-10">
					<select id="provinces" name="provinces" class="form-control">
						<option value="0">-----<?php echo lang('msg_county')?>------</option>
						<?php foreach($data['provinces'] as $r){?>
						<option value="<?php echo $r->id?>"><?php echo $r->name;?></option>
						<?php };?>
					</select>
					<?php echo form_error('provinces')?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo lang('msg_city')?></label>
				<div class="col-sm-10">
					<select id="cities" name="cities" class="form-control">
						<option value="">-----<?php echo lang('msg_city');?>------</option>
						<?php foreach($data['cities'] as $r){?>
						<option value="<?php echo $r->id?>"><?php echo $r->name?></option>
						<?php }?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" for="txtName"><?php echo lang('msg_purpose')?></label>
				<div class="col-sm-10">
					<select id="aim" name="aim" class="form-control">
						<option value="">-----<?php echo lang('msg_purpose')?>-----</option>
						<option value="<?php echo BUY?>"><?php echo lang('msg_buy')?></option>
						<option value="<?php echo SELL?>"><?php echo lang('msg_sell')?></option>
					</select>
					<?php echo form_error('aim')?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" for="txtName"><?php echo lang('content')?></label>
				<div class="col-sm-10">
					<textarea id="content" name="content" class="form-control"><?php echo set_value('content')?></textarea>
					<?php echo form_error('content')?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" for="txtName"><?php echo lang('msg_images')?></label>
				<div class="col-sm-10">
					<input type="file" name="image" id="image">
					<?php if(isset($error['error_upload_file'])){?>
					<span class="help-inline msg-error" generated="true"><?php echo $error['error_upload_file']?></span>
					<?php }; ?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" for="txtName"></label>
				<div class="col-sm-10">
					<input type="file" name="image1" id="image1">
					<?php if(isset($error['error_upload_file'])){?>
					<span class="help-inline msg-error" generated="true"><?php echo $error['error_upload_file_1']?></span>
					<?php }; ?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" for="txtName"></label>
				<div class="col-sm-10">
					<input type="file" name="image2" id="image2">
					<?php if(isset($error['error_upload_file'])){?>
					<span class="help-inline msg-error" generated="true"><?php echo $error['error_upload_file_2']?></span>
					<?php };?>
				</div>
			</div>
			

			<div class="col-sm-10 col-sm-offset-2">
				<button type="submit" class="btn btn-primary" >
					<?php echo lang('msg_save')?>
				</button>
				<button type="reset" class="btn"> 
					<?php echo lang('msg_reset')?>
				</button>
			</div>

		</fieldset>
	</form>

	<!--end form-->

	<!--end container fluid-->
	<script>
	jQuery(document).ready(function($) {
		$('#provinces').change(function(){
			$county_id=$(this).val();
			$.ajax({
				url: '<?php echo base_url()?>admin/cities/get_city',
				type: 'POST',
				dataType: 'html',
				data: {county_id: $county_id },
			})
			.done(function() {
				console.log("success");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function(data) {
				$('#cities').html(data);
			});
		})

		

		$('#content').tinymce({
                        // Location of TinyMCE script
                        script_url : '<?php echo base_url()?>statics/tinymce/tiny_mce.js',
                        language : "vi",
                        width:'100%',
                        height:'500',
                        // General options
                        theme : "advanced",
                        plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

                        // Theme options
                        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
                        theme_advanced_toolbar_location : "top",
                        theme_advanced_toolbar_align : "left",
                        theme_advanced_statusbar_location : "bottom",
                        theme_advanced_resizing : true,

                        // Example content CSS (should be your site CSS)
                        content_css : "css/content.css",

                        // Drop lists for link/image/media/template dialogs
                        template_external_list_url : "lists/template_list.js",
                        external_link_list_url : "lists/link_list.js",
                        external_image_list_url : "lists/image_list.js",
                        media_external_list_url : "lists/media_list.js",

                        // Replace values for the template plugin
                        template_replace_values : {
                        	username : "Some User",
                        	staffid : "991234"
                        }
                    });	
});
</script>

