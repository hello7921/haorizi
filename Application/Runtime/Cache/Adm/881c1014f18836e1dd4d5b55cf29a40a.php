<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link href="/haorizi/Public/public/css/admin/style.css" rel="stylesheet"/>
	<title><?php echo L('website_manage');?></title>
	<script>
	var URL = '/haorizi/amd.php?s=/Article_cate';
	var SELF = '/haorizi/amd.php?s=/article_cate/index/menuid/360.html';
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

<!--栏目列表-->
<div class="pad_10">
    <div class="J_tablelist table_list" data-acturi="<?php echo U(CONTROLLER_NAME.'/ajax_edit');?>">
    <table width="100%" cellspacing="0" id="J_cate_tree">
        <thead>
            <tr>
                <th width="30"><input type="checkbox" name="checkall" class="J_checkall"></th>
              	<th align="left"><?php echo L('article_cate_name');?></th>
              	<th align="left"><?php echo L('article_cate_ename');?></th>
                <th width="60"><span data-tdtype="order_by" data-field="id">ID</span></th>
                <th width="60"><?php echo L('article_cate_type');?></th>
				<th width="100"><?php echo L('article_cate_img');?></th>
              	<th width="80"><span data-tdtype="order_by" data-field="ordid"><?php echo L('sort_order');?></span></th>
                <th width="60"><span data-tdtype="order_by" data-field="status"><?php echo L('status');?></span></th>
                <th width="280"><?php echo L('operations_manage');?></th>
            </tr>
        </thead>
    	<tbody>
        <?php echo ($list); ?>
    	</tbody>
    </table>
    </div>
    <div class="btn_wrap_fixed">
        <label class="select_all"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
        <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U(CONTROLLER_NAME.'/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="<?php echo L('delete');?>" />
        <input type="button" class="btn J_showdialog btn_submit"  data-id="dai3d6"     data-uri="<?php echo U('article_cate/addmult');?>" data-title="批量添加"  value="批量添加" />
   
    </div>
</div>
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
<script src="/haorizi/Public/public/js/jquery/plugins/jquery.treetable.js"></script>
<script>
$(function(){
    //initialState:'expanded'
    $("#J_cate_tree").treeTable({indent:20,treeColumn:1});
    $(".J_preview").preview();
});
</script>
</body>
</html>