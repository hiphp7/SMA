<?php
echo ace_form_open('','',array('id'=>$item->mobilenum));
?>
<div class="form-group">
<label for="bpoid_sponsor" class="col-xs-12 col-sm-2 control-label no-padding-right"><span class="red">*</span>选择分组:</label><div class="col-xs-12 col-sm-5"><select name="groupId" id="from" style="width: 100%;">
<?php
foreach($groups as $k =>$v){
				$select = $v->groupid == $itme->groupid ?'selected':'';
				echo '<option value="'.$v->groupid.'" '.$select.' >'.$v->groupname."</option>\n";
}
?>
<select>
</div>
</div>
<?php 
	$options = array(
					'label_text'=>'电话号码',
					'datatype'=>'n11-11', 
					'nullmsg'=>"请输入电话号码！",
					'errormsg'=>"请输电话号码", 
					'help'=>'电话号码'
					);
	echo ace_input_m($options,'mobileNum',$item->mobilenum,'maxlength="11"');
	$options = array(
					'label_text'=>'用户名',
					'datatype'=>'*1-15', 
					'nullmsg'=>"请输入用户名！",
					'errormsg'=>"请输用户名", 
					'help'=>'用户名'
					);
	echo ace_input_m($options,'userName',$item->username,'maxlength="15"');
echo ace_srbtn('bp/channelManage/rouji/info',false,'确认保存');
echo ace_form_close();
?>
<script>
$(function (){
						$("#mobileNum").change(function(){
            mobile = $("#mobileNum").val();
            if(mobile !=""){
                $.post("<?php echo base_url('bp/channelManage/rouji/ajaxMobile')?>",
                   { mobile: mobile } ,
                   function(data){
                       result = eval(data);
                       if(result.status == 1){
                           $("#mobileNum").val("");
                           $("#mobileNum").focus();
                            layer.alert(result.info);
                       }
                    },"json"    
                );      
            }   
        });

});
</script>
