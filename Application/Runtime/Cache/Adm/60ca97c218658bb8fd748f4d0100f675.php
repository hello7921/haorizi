<?php if (!defined('THINK_PATH')) exit();?><!--编辑栏目-->
<div class="dialog_content">
	<form id="info_form" action="<?php echo u(CONTROLLER_NAME.'/'.ACTION_NAME);?>" method="post">
	<table width="100%" class="table_form">
		<tr>
			<th width="120"><?php echo L('article_cate_parent');?> :</th>
			<td>
				<select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U(CONTROLLER_NAME.'/ajax_getchilds');?>" data-selected="<?php echo ($info["spid"]); ?>"></select>
				<input type="hidden" name="pid" id="J_cate_id" />
			</td>
		</tr>
		<tr>
			<th><?php echo L('article_cate_name');?> :</th>
			<td><input type="text" name="name" id="name" class="input-text" value="<?php echo ($info["name"]); ?>" size="30"></td>
		</tr>
		<tr>
			<th><?php echo L('article_cate_type');?> :</th>
			<td>
				<label><input type="radio" name="type" value="0" <?php if($info["type"] == 0): ?>checked<?php endif; ?>> <?php echo L('article_cate_type_0');?></label>&nbsp;&nbsp;
                <label><input type="radio" name="type" value="1" <?php if($info["type"] == 1): ?>checked<?php endif; ?>> <?php echo L('article_cate_type_1');?></label>
			</td>
		</tr>
	    <tr>
			<th><?php echo L('article_cate_img');?> :</th>
			<td>
                <input type="text" name="img" id="J_img" class="input-text fl mr10" size="30" value="<?php echo ($info["img"]); ?>">
            	<div id="J_upload_img" class="upload_btn"><span><?php echo L('upload');?></span></div>
                <?php if(!empty($info['img'])): ?><span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo ($info["_img"]); ?>"></span><?php endif; ?></td>
		</tr>
	    <tr>
			<th><?php echo L('enabled');?> :</th>
			<td>
				<label><input type="radio" name="status" value="1" <?php if($info["status"] == 1): ?>checked<?php endif; ?> > <?php echo L('yes');?></label>&nbsp;&nbsp;
				<label><input type="radio" name="status" value="0" <?php if($info["status"] == 0): ?>checked<?php endif; ?> > <?php echo L('no');?></label>
			</td>
		</tr>
		<tr>
			<th><?php echo L('seo_title');?> :</th>
			<td><input type="text" name="seo_title" id="seo_title" class="input-text" value="<?php echo ($info["seo_title"]); ?>" size="50"></td>
		</tr>
		<tr>
			<th><?php echo L('seo_keys');?> :</th>
			<td><input type="text" name="seo_keys" id="seo_keys" class="input-text" value="<?php echo ($info["seo_keys"]); ?>" size="50"></td>
		</tr>
		<tr>
			<th><?php echo L('seo_desc');?> :</th>
			<td><textarea name="seo_desc" style="width:300px; height:50px;"><?php echo ($info["seo_desc"]); ?></textarea></td>
		</tr>
	</table>
	<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
	</form>
</div>
<script src="/haorizi/Public/public/js/admin.js"></script>
<script src="/haorizi/Public/public/js/fileuploader.js"></script>
<script>
$(function(){

	$('.J_cate_select').cate_select();
	
	//上传图片
    var uploader = new qq.FileUploaderBasic({
    	allowedExtensions: ['jpg','gif','jpeg','png','bmp','pdg'],
        button: document.getElementById('J_upload_img'),
        multiple: false,
        action: "<?php echo U('ArticleCate/ajax_upload_img');?>",
        inputName: 'img',
        forceMultipart: true, //用$_FILES
        messages: {
        	typeError: lang.upload_type_error,
        	sizeError: lang.upload_size_error,
        	minSizeError: lang.upload_minsize_error,
        	emptyError: lang.upload_empty_error,
        	noFilesError: lang.upload_nofile_error,
        	onLeave: lang.upload_onLeave
        },
        showMessage: function(message){
        	$.zhiphp.tip({content:message, icon:'error'});
        },
        onSubmit: function(id, fileName){
        	$('#J_upload_img').addClass('btn_disabled').find('span').text(lang.uploading);
        },
        onComplete: function(id, fileName, result){
        	$('#J_upload_img').removeClass('btn_disabled').find('span').text(lang.upload);
            if(result.status == '1'){
        		$('#J_img').val(result.data);
        	} else {
        		$.zhiphp.tip({content:result.msg, icon:'error'});
        	}
        }
    });
    $("#name").formValidator({onshow:lang.please_input+lang.article_cate_name,onfocus:lang.please_input+lang.article_cate_name}).inputValidator({min:1,onerror:lang.please_input+lang.article_cate_name}).defaultPassed();

});
</script>