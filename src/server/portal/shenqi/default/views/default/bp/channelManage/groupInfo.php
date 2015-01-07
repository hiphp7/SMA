<?php
echo ace_form_open('','',array('id'=>$item->groupid));
?>
<?php 
	$options = array(
					'label_text'=>'组名称',
					'datatype'=>'*', 
					'nullmsg'=>"请输入组名称！",
					'errormsg'=>"请输组名称", 
					'help'=>'组名称'
					);
	echo ace_input_m($options,'groupname',$item->groupname,'maxlength="15"');
echo ace_srbtn('bp/channelManage/groups/info',TRUE,'确认保存','');
echo ace_form_close();
?>
<script>
    $(function(){
        $("#groupname").change(function(){
            
            name = $("#groupname").val();
            
            if(name !=""){
                $.post("<?php echo base_url('bp/channelManage/groups/ajaxName')?>",
                   { groupname: name }	,
                   function(data){
                       result = eval(data);
                       if(result.status == 1){
                           $("#groupname").val("");
                           $("#groupname").focus();
                            layer.alert(result.info);
                       }
                    },"json"	
                );	
            } 	
     });
     });
</script>
