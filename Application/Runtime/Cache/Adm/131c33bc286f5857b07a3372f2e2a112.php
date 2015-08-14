<?php if (!defined('THINK_PATH')) exit();?><!--添加栏目-->
<div class="dialog_content">
	<form id="info_form" action="<?php echo u(CONTROLLER_NAME.'/'.ACTION_NAME);?>" method="post">
	<table width="100%" class="table_form"> 
		<tr>
			<th>名称 :</th>
			<td>
                           <textarea name="name" style="width:250px; height:100px;"></textarea>一行一个
                        </td>
		</tr> 
	</table>
	</form>
</div>
<script src="/haorizi/Public/public/js/admin.js"></script>