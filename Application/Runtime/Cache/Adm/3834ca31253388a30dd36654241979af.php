<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link href="/haorizi/Public/public/css/admin/style.css" rel="stylesheet"/>
	<title><?php echo L('website_manage');?></title>
	<script>
	var URL = '/haorizi/amd.php?s=/Article';
	var SELF = '/haorizi/amd.php?c=Article&a=index&menuid=&time_start=&time_end=&cate_id=0&status=&keyword=&search=%E6%90%9C%E7%B4%A2';
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

<!--文章列表-->
<div class="pad_10" >
    <form name="searchform" method="get" >
    <table width="100%" cellspacing="0" class="search_form">
        <tbody>
        <tr>
            <td>
            <div class="explain_col">
                <!--<input type="hidden" name="g" value="admin" />-->
                <input type="hidden" name="c" value="Article" />
                <input type="hidden" name="a" value="index" />
                <input type="hidden" name="menuid" value="<?php echo ($menuid); ?>" />
                <?php echo L('publish_time');?>：
                <input type="text" name="time_start" id="time_start" class="date J_date_picker" size="12" value="<?php echo ($search["time_start"]); ?>">
                -
                <input type="text" name="time_end" id="time_end" class="date J_date_picker" size="12" value="<?php echo ($search["time_end"]); ?>">
                &nbsp;&nbsp;<?php echo L('article_cateid');?>：
                <select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('ArticleCate/ajax_getchilds');?>" data-selected="<?php echo ($search["selected_ids"]); ?>"></select>
                <input type="hidden" name="cate_id" id="J_cate_id" value="<?php echo ($search["cate_id"]); ?>" />
                &nbsp;&nbsp;<?php echo L('status');?>:
                <select name="status">
                <option value="">-<?php echo L('all');?>-</option>
                <option value="1" <?php if($search["status"] == '1'): ?>selected="selected"<?php endif; ?>>已审核</option>
                <option value="0" <?php if($search["status"] == '0'): ?>selected="selected"<?php endif; ?>>未审核</option>
                </select>
                &nbsp;&nbsp;<?php echo L('keyword');?> :
                <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($search["keyword"]); ?>" />
                <input type="submit" name="search" class="btn" value="<?php echo L('search');?>" />
            </div>
            </td>
        </tr>
        </tbody>
    </table>
    </form>
    <div class="J_tablelist table_list" data-acturi="<?php echo U(CONTROLLER_NAME.'/ajax_edit');?>">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width=25><input type="checkbox" id="checkall_t" class="J_checkall"></th>
                <th width="30">序号</th>
                <th><span data-tdtype="order_by" data-field="id">ID</span></th>
                <th align="left"><span data-tdtype="order_by" data-field="title"><?php echo L('article_title');?></span></th>
                <th><span data-tdtype="order_by" data-field="cate_id"><?php echo L('article_cateid');?></span></th>
                <th width=150><?php echo L('author');?></th>
				<th width=150><span data-tdtype="order_by" data-field="add_time"><?php echo L('publish_time');?></span></th>
                <th width=60><span data-tdtype="order_by" data-field="ordid"><?php echo L('sort_order');?></span></th>
                <th width="40"><span data-tdtype="order_by" data-field="status"><?php echo L('status');?></span></th>
                <th width="80"><?php echo L('operations_manage');?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                <td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
                <td align="center"><?php echo ($p*20-20+$i); ?></td>
                <td align="center"><?php echo ($val["id"]); ?></td>
                <td align="left">
                    <span data-tdtype="edit" data-field="title" data-id="<?php echo ($val["id"]); ?>" class="tdedit" style="color:<?php echo ($val["colors"]); ?>;">
                        <?php echo ($val["title"]); ?>
                    </span>
                </td>
                <td align="center"><b><?php echo ($cate_list[$val['cate_id']]); ?></b></td>
				<td align="center"><b><?php echo ($val['author']); ?></b></td>
                <td align="center"><?php echo (date('Y-m-d H:i:s',$val["add_time"])); ?></td>
                <td align="center"><span data-tdtype="edit" data-field="ordid" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["ordid"]); ?></span></td>
                <td align="center"><img data-tdtype="toggle" data-id="<?php echo ($val["id"]); ?>" data-field="status" data-value="<?php echo ($val["status"]); ?>" src="/haorizi/Public/public/css/admin/bgimg/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" /></td>
                <td align="center"><a href="<?php echo u(CONTROLLER_NAME.'/edit', array('id'=>$val['id'], 'menuid'=>$menuid));?>"><?php echo L('edit');?></a> | <a href="javascript:void(0);" class="J_confirmurl" data-acttype="ajax" data-uri="<?php echo u(CONTROLLER_NAME.'/delete', array('id'=>$val['id']));?>" data-msg="<?php echo sprintf(L('confirm_delete_one'),$val['title']);?>"><?php echo L('delete');?></a>
                    </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="btn_wrap_fixed">
        <label class="select_all"><input type="checkbox" name="checkall" class="J_checkall"/><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
        <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U(CONTROLLER_NAME.'/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="<?php echo L('delete');?>" />
        <div id="pages"><?php echo ($page); ?></div>
    </div>
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
<script>
$('.J_cate_select').cate_select({top_option:lang.all});
</script>
</body>
</html>