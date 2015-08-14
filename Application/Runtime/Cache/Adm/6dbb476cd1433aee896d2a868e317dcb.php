<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link href="/haorizi/Public/public/css/admin/style.css" rel="stylesheet"/>
	<title><?php echo L('website_manage');?></title>
	<script>
	var URL = '/haorizi/amd.php?s=/Article_cate';
	var SELF = '/haorizi/amd.php?s=/article_cate/add/pid/4.html';
	var ROOT_PATH = '/haorizi';
	var APP	 =	 '/haorizi/amd.php?s=';
	//语言项目
	var lang = new Object();
	<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?>
	</script>
</head>

<body>
<div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div>

<div class="subnav">
    <div class="content_menu ib_a blue line_x">
   <?php if(($sub_menu != '') OR ($big_menu != '')): if(!empty($big_menu)): if( ($big_menu["url"] != '')): ?><a class="add fb" href="<?php echo ($big_menu["url"]); ?>" id="<?php echo ($big_menu["id"]); ?>" ><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; ?>
           <?php if( ($big_menu["iframe"] != '')): ?><a class="add fb J_showdialog" href="javascript:void(0);" id="<?php echo ($big_menu["id"]); ?>" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; endif; ?>

       <?php if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?>
               <?php if( ($val['group_name'] != '')): ?><a href="<?php echo U($val['group_name'].'/'.$val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo L($val['name']);?></em></a>
                   <?php else: ?>
                   <a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo L($val['name']);?></em></a><?php endif; endforeach; endif; else: echo "" ;endif; endif; endif; ?>
    </div>
</div>

<!--添加文章-->
<form id="info_form" action="<?php echo u(CONTROLLER_NAME.'/'.ACTION_NAME);?>" method="post" enctype="multipart/form-data">
    <div class="pad_10">
        <div class="col_tab">
            <ul class="J_tabs tab_but cu_li">
                <li class="current"><?php echo L('article_basic');?></li>
                <li><?php echo L('article_seo');?></li>
            </ul>
            <div class="J_panes">
                <div class="content_list pad_10">
                    <table width="100%" cellspacing="0" class="table_form">
                      <tr>
			<th width="120"><?php echo L('article_cate_parent');?> :</th>
			<td>
				<select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U(CONTROLLER_NAME.'/ajax_getchilds');?>" data-selected="<?php echo ($spid); ?>"></select>
				<input type="hidden" name="pid" id="J_cate_id" />
			</td>
		</tr>
		<tr>
			<th><?php echo L('article_cate_name');?> :</th>
			<td><input type="text" name="name" id="name" class="input-text" size="30"></td>
		</tr>
        <tr>
			<th><?php echo L('article_cate_ename');?> :</th>
			<td><input type="text" name="ename" id="ename" class="input-text" size="30"></td>
		</tr>
		<tr>
			<th><?php echo L('article_cate_type');?> :</th>
			<td>
				<label><input type="radio" name="type" value="0" checked> <?php echo L('article_cate_type_0');?></label>&nbsp;&nbsp;
                <label><input type="radio" name="type" value="1"> <?php echo L('article_cate_type_1');?></label>
			</td>
		</tr>
        <tr>
            <th><?php echo L('article_cate_img');?> :</th>
            <td>
            	<input type="text" name="img" id="J_img" class="input-text fl mr10" size="30">
            	<div id="J_upload_img" class="upload_btn"><span><?php echo L('upload');?></span></div>
            </td>
        </tr>
        <tr>
		<th><?php echo L('enabled');?> :</th>
            <td>
                <label><input type="radio" name="status" value="1" checked> <?php echo L('yes');?></label>&nbsp;&nbsp;
                <label><input type="radio" name="status" value="0"> <?php echo L('no');?></label>
            </td>
        </tr>
  </table>
                </div>
                <div class="content_list pad_10 hidden">
                    <table width="100%" cellspacing="0" class="table_form">
                       <tr>
			<th><?php echo L('seo_title');?> :</th>
			<td><input type="text" name="seo_title" id="seo_title" class="input-text" style="width:300px;"></td>
		</tr>
		<tr>
			<th><?php echo L('seo_keys');?> :</th>
			<td><input type="text" name="seo_keys" id="seo_keys" class="input-text" style="width:300px;"></td>
		</tr>
		<tr>
			<th><?php echo L('seo_desc');?> :</th>
			<td><textarea name="seo_desc" style="width:300px; height:50px;"></textarea></td>
		</tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="btn_wrap_fixed">
            <input type="submit" value="<?php echo L('submit');?>" id="dosubmit1" name="dosubmit1" class="btn btn_submit" style="margin:0 0 5px 5px;">
        </div>
    </div>
</form>
<script src="/haorizi/Public/public/js/jquery/jquery.js"></script>
<script src="/haorizi/Public/public/js/jquery/plugins/jquery.tools.min.js"></script>
<script src="/haorizi/Public/public/js/jquery/plugins/formvalidator.js"></script>
<script src="/haorizi/Public/public/js/zhiphp.js"></script>
<script src="/haorizi/Public/public/js/admin.js"></script>
<script>
//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script>

<?php if(isset($list_table)): ?><script src="/haorizi/Public/public/js/jquery/plugins/listTable.js"></script>
<script>
$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?>
<script src="/haorizi/Public/public/js/admin.js"></script>
<script src="/haorizi/Public/public/js/fileuploader.js"></script>
<script>
$(function(){
    $('ul.J_tabs').tabs('div.J_panes > div');
    $('.J_cate_select').cate_select("顶级栏目");
    $("#name").blur(function() {
        var val = $(this).val();
        if(val!==''){
            $.get("<?php echo u('ArticleCate/ajax_ping');?>", {title: val}, function(jsondata) {
                $("#ename").val(jsondata);
//                var return_data = eval("(" + jsondata + ")");
//                $(this).val(return_data.data);
            });
        }
    });
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
});
$("#name").formValidator({onshow:lang.please_input+lang.article_cate_name,onfocus:lang.please_input+lang.article_cate_name}).inputValidator({min:1,onerror:lang.please_input+lang.article_cate_name});
//上传图片



</script>
</body>
</html>