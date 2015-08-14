<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link href="/haorizi/Public/public/css/admin/style.css" rel="stylesheet"/>
	<title><?php echo L('website_manage');?></title>
	<script>
	var URL = '/haorizi/amd.php?s=/Index';
	var SELF = '/haorizi/amd.php?s=/Index/panel.html';
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

<div class="pad_10">
	<?php if(!empty($message)): ?><ul id="message_list">
    	<?php if(is_array($message)): $i = 0; $__LIST__ = $message;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="list_<?php echo ($val["type"]); ?>"><?php echo ($val["content"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul><?php endif; ?>

	<div class="clearfix">
        <div class="col-2">
            <h6> </h6>
            <div class="content"> 
            </div>
        </div>
        <div class="col-2 mt10">
            <h6>系统信息</h6>
            <div class="content">
                <table class="table_panel lh26" style="width: 100%;">
                    <tr>
                        <td width="20%">版本：</td>
                        <td width="30%">1.0</td>
                        <td width="20%"><?php echo L('server_domain');?>：</td>
                        <td width="30%"><?php echo ($system_info["server_domain"]); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo L('server_os');?>：</td>
                        <td><?php echo ($system_info["server_os"]); ?></td>
                        <td><?php echo L('web_server');?>：</td>
                        <td><?php echo ($system_info["web_server"]); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo L('php_version');?>：</td>
                        <td><?php echo ($system_info["php_version"]); ?></td>
                        <td><?php echo L('mysql_version');?>：</td>
                        <td><?php echo ($system_info["mysql_version"]); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo L('upload_max_filesize');?>：</td>
                        <td><?php echo ($system_info["upload_max_filesize"]); ?></td>
                        <td><?php echo L('max_execution_time');?>：</td>
                        <td><?php echo ($system_info["max_execution_time"]); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo L('safe_mode');?>：</td>
                        <td><?php echo ($system_info["safe_mode"]); ?></td>
                        <td><?php echo L('zlib');?>：</td>
                        <td><?php echo ($system_info["zlib"]); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo L('curl');?>：</td>
                        <td><?php echo ($system_info["curl"]); ?></td>
                        <td><?php echo L('timezone');?>：</td>
                        <td><?php echo ($system_info["timezone"]); ?></td>
                    </tr>
                </table>
            </div>
        </div>   
                 
        <div class="col-2 mt10">
            <h6>开发团队</h6>
            <div class="content">
                <table class="table_panel lh26" style="width: 100%;">
                    
                </table>
            </div>
        </div>         
    </div>
</div>
</body>
</html>