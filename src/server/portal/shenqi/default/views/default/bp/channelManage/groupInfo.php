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
?>
<div class="clearfix form-actions">
                      <div class="col-md-offset-3 col-md-9">
                          <button id="sub-btn" class="btn btn-success" type="submit">
                              <i class="icon-ok bigger-110"></i> 确认保存
                          </button> 	  <a href="javascript:;" class="btn btn-info" onclick="history.go(-1)">
	                         <i class="icon-reply"></i>返回上一页
	                      </a>
												<a href="<?php echo base_url('bp/channelManage/groups');?>" class="btn btn-info">
                             <i class="icon-list"></i>返回列表
	                      </a>	</div>
                  </div>
<?php
echo ace_form_close();
?>
<script>
var  flag =false;
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
                       }else{
											 flag=true;
											 }
                    },"json"	
                );	
            } 	
     });
				$("#sub-btn").click(function (){
												if(flag!=true)
												{
												return false;
												}

});
     });
</script>
