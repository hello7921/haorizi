<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content">
	<form id="info_form" name="info_form" action="<?php echo u(CONTROLLER_NAME.'/'.ACTION_NAME);?>"  method="post">
	<table width="100%" class="table_form">
		<tr> 
	      <th width="80"><?php echo L('admin_username');?> :</th>
	      <td><input type="text" name="username" id="J_username" class="input-text"></td>
	    </tr>
	    <tr> 
	      <th><?php echo L('password');?> :</th>
	      <td><input type="password" name="password" id="J_password" class="input-text"></td>
	    </tr>
	    <tr>
	      <th><?php echo L('cofirmpwd');?> :</th>
	      <td><input type="password" name="repassword" id="J_repassword" class="input-text"></td>
	    </tr>
	    <tr>
	    	<th><?php echo L('admin_email');?> :</th>
	    	<td><input type="text" name="email" class="input-text" size="30"></td>
	    </tr>
	    <tr>
	      <th><?php echo L('admininrole');?> :</th>
	      <td>
	      	<select name="role_id">
	        	<?php if(is_array($role_list)): $i = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	        </select>
	      </td>
	    </tr>
	</table>
	</form>
</div>
<script>
$(function(){
	$.formValidator.initConfig({formid:"info_form",autotip:true});
	
	$("#J_username").formValidator({ onshow:lang.please_input+lang.admin_username, onfocus:lang.please_input+lang.admin_username}).inputValidator({ min:1, onerror:lang.please_input+lang.admin_username}).ajaxValidator({type:"get", url:"", data:"c=Admin&a=ajax_check_name", datatype:"html", async:'false', success:function(data){ if(data == "1"){return true;}else{return false;}}, onerror:lang.admin_name_exists, onwait:lang.connecting_please_wait});
	$("#J_password").formValidator({ onshow:lang.please_input+lang.password, onfocus:lang.password+lang.between_6_to_20}).inputValidator({ min:6, max:20, onerror:lang.password+lang.between_6_to_20});
	$("#J_repassword").formValidator({ onshow:lang.cofirmpwd, onfocus:lang.cofirmpwd}).compareValidator({desid:"J_password",operateor:"=",onerror:lang.passwords_not_match});

	$('#info_form').ajaxForm({success:complate,dataType:'json'});
    function complate(result){
        if(result.status == 1){
            $.dialog.get(result.dialog).close();
            $.ZhiPHP.tip({content:result.msg});
            window.location.reload();
        } else {
            $.ZhiPHP.tip({content:result.msg, icon:'alert'});
        }
    }
});
</script>