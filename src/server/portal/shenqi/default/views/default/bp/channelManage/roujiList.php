<div class="row">
    <div class="col-xs-12">

        <div class="table-responsive">
            <div class="dataTables_wrapper">   
				<div class="row">
					<div class="col-sm-12">
					    <form method="get" action="">
                            <label class="col-sm-3">
                            手机号：<input type="text" class="input-text" name="mobileNum" value="<?php echo $mobileNum?>" />
                            </label>
                            <label class="col-sm-3">
                            姓名：<input type="text" class="input-text" name="userName" value="<?php echo $userName?>" />
                            </label>
                            <label class="col-sm-4">
                            组名：<select type="text" class="input-text" name="groupId" value="<?php echo $groupId?>" >
																			<option value="" >全部</option>
																		<?php foreach($groups as $v){ 
																					$select = $groupId==$v->groupid?'selected':'';
																				?>
																					<option value="<?php echo $v->groupid.'" '.$select ;?> ><?php echo $v->groupname ?> </option>
																		<?php }?>
																	</select>
                            </label>
                            <label class="col-sm-1">
                                <button class="btn btn-sm btn-primary" type="submit">
                                   <i class="icon-search"></i>搜索
                                </button>
                            </label>
						    <label class="col-sm-1">
							    <a class="btn btn-sm btn-primary" href="<?php echo base_url('bp/channelManage/rouji/info')?>">
	                                <i class="icon-plus"></i>添加
	                            </a>
						    </label>
                        </form>  
					</div>
				</div>
				<table class="table table-striped table-bordered table-hover dataTable">
				    <colgroup>
				       <col width="3%">
				       <col width="10%">
				       <col width="10%">
				       <col width="10%">
				       <col width="10%">
				       <col width="5%">
				    </colgroup>
	                <thead>
	                    <tr>
                            <th>序号</th>
                            <th>手机号</th>
                            <th >姓名</th>
                            <th>添加时间</th>
                            <th>所属组</th>
                            <th>操作</th>
	                    </tr>
	                </thead>
	
	                <tbody id="tbody_content">
                    
                     <?php

    if($lc_list){?>
                          <?php foreach($lc_list as $key =>$item):?>
                          <tr>
                            <td><?php echo ($key+1)+$page ;?></td>
                            <td><?php echo $item->mobilenum;?></td>
                            <td><?php echo $item->username;?></td>
                            <td><?php echo $item->createtime;?></td>
                            <td><?php echo $item->groupname;?></td>
                            <td>
														<div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
	                                <a class="red tooltip-success" href="javascript:" onclick="msgdelete(<?php echo $item->mobilenum?>)" data-rel="tooltip" title="删除">
	                                    <i class="icon-trash bigger-130"></i>
	                                </a>
 	                                <a class="red tooltip-success" href="javascript:" onclick="group(<?php echo $item->mobilenum?>)" data-rel="tooltip" title="移动">
 	                                    <i class="icon-exchange bigger-130"></i>
 	                                </a>
																	</div>
														</td>
                          </tr>
                          <?php endforeach;?>
                     <?php }else{?>
														<tr><td colspan="8" style="text-align:center;">未找到数据</td></tr>
 											<?php	}?>
	                </tbody>
	            </table>
	            
			    <?php $this->load->view('admin/common/page')?>
            </div>
        </div>
    </div>
</div>
 <script>
 function  group(mobile){
 								$.layer({
 type: 2,
 maxmin: true,
 shadeClose: true,
 title: '',
 offset: ['20px',''],
 area: ['600px', ($(window).height() - 50)+'px'],
 iframe: {src: 'rouji/moveGroup?mobile='+mobile+'&'+Math.random()}
 ,close: function (i){
 layer.close(i);
 window.location.href='<?php echo base_url('bp/channelManage/rouji?mobileNum='.$mobileNum.'&groupId='.$groupId.'&userName='.$userName);?>';
 }
 ,btns:1
 ,yes: function(i){
 mobile=layer.getChildFrame("#mobile").val();
 groupid=layer.getChildFrame("#groupid").val();
 $.post('rouji/moveGroup',{mobile:mobile,groupId:groupid},function (data){
	 if(data.status == 0)
	 {
	   layer.alert(data.info,3);
	     layer.close(i);
	       return ;
	       }
	       layer.alert(data.info,1,'确认',function(){
		       window.location.href='<?php echo base_url('bp/channelManage/rouji?mobileNum='.$mobileNum.'&groupId='.$groupId.'&userName='.$userName);?>';
		       });
	       layer.close(i);
	       },'json');
 return;
 }
 }); 
 }
 </script>
