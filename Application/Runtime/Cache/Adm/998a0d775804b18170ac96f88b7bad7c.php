<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link href="/haorizi/Public/public/css/admin/style.css" rel="stylesheet"/>
	<title><?php echo L('website_manage');?></title>
	<script>
	var URL = '/haorizi/amd.php?s=/Article';
	var SELF = '/haorizi/amd.php?s=/article/add/menuid/353.html';
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
						<th width="120"><?php echo L('article_cateid');?> :</th>
						<td>
                            <select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('ArticleCate/ajax_getchilds');?>" data-selected=""></select>
                            <input type="hidden" name="cate_id" id="J_cate_id" value="" />
                        </td>
					</tr>
		            <tr>
						<th><?php echo L('article_title');?> :</th>
						<td>
							<input type="text" name="title" id="J_title" class="input-text" size="30">
                            <input type="hidden" value="" name="colors" id="title_color">
                            <a href="javascript:;" class="color_picker_btn"><img class="J_color_picker" data-it="J_title" data-ic="J_color" src="/haorizi/Public/public/statics/admin/images/color.png"></a>
		                </td>
					</tr>
		            <tr>
						<th><?php echo L('tag');?> :</th>
						<td>
		                	<input type="text" name="tags" id="J_tags" class="input-text" size="50">
		                    <input type="button" value="<?php echo L('auto_get');?>" id="J_gettags" name="tags_btn" class="btn">
		                </td>
					</tr>
		            <tr>
						<th><?php echo L('author');?> :</th>
						<td><input type="text" name="author" id="author" class="input-text" size="30" value="<?php echo ($author); ?>"></td>
					</tr>
		            <tr>
						<th><?php echo L('article_img');?> :</th>
						<td><input type="file" name="img" id="img" class="input-text"  style="width:200px;" /></td>
		 			</tr>
					<tr>
						<th><?php echo L('publish');?> :</th>
		 				<td>
		                	<label><input type="radio" name="status" class="radio_style" value="1"> <?php echo L('yes');?> </label>&nbsp;&nbsp;
							<label><input type="radio" name="status" class="radio_style" value="0" checked="checked"> <?php echo L('no');?></label>
						</td>
					</tr>
                    <tr>
						<th><?php echo L('article_abst');?> :</th>
						<td><textarea name="intro" id="abst" style="width:67%;height:50px;resize:none;"></textarea></td>
					</tr>
		            <tr>
						<th>详细内容 :</th>
		                <td><textarea name="content" id="content" style="width:68%;height:400px;visibility:hidden;resize:none;"></textarea></td>
					</tr>
				</table>
			</div>
			<div class="content_list pad_10 hidden">
				<table width="100%" cellspacing="0" class="table_form">
					<tr>
						<th width="120"><?php echo L('seo_title');?> :</th>
		 				<td><input type="text" name="seo_title" id="seo_title" class="input-text" size="60"></td>
					</tr>
					<tr>
						<th><?php echo L('seo_keys');?> :</th>
						<td><input type="text" name="seo_keys" id="seo_keys" class="input-text" size="60"></td>
					</tr>
					<tr>
						<th><?php echo L('seo_desc');?> :</th>
						<td><textarea name="seo_desc" id="seo_desc" cols="80" rows="8"></textarea></td>
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
<script src="/haorizi/Public/public/js/jquery/plugins/colorpicker.js"></script>
<script src="/haorizi/Public/public/js/kindeditor/kindeditor-min.js"></script>
<script>
$(function() {
    /**
     *  栏目联动选择
     */
    $('.J_cate_select').cate_select('请选择');
    //颜色选择器
    $('.J_color_picker').colorpicker();
   /*
    *  图文编辑框
    */
	KindEditor.create('#content', {
		uploadJson : '<?php echo U("attachment/editer_upload");?>',
		fileManagerJson : '<?php echo U("attachment/editer_manager");?>',
		allowFileManager : true
	});
    /**
     * tabs 选择标签
     */
	$('ul.J_tabs').tabs('div.J_panes > div');
    ;
    //自动获取标签
    $('#J_gettags').live('click', function() {
        var title = $.trim($('#J_title').val());
        if(title == ''){
            $.zhiphp.tip({content:lang.article_title_isempty, icon:'alert'});
            return false;
        }
        $.getJSON('<?php echo U("Article/ajax_gettags");?>', {title:title}, function(result){
            if(result.status == 1){
                $('#J_tags').val(result.data);
            }else{
                $.zhiphp.tip({content:result.msg});
            }
        });
    });
});

//颜色选择器
//$('.J_color_picker').colorpicker();
</script>
</body>
</html>