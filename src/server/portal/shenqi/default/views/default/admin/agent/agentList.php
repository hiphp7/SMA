<div class="row">
    <div class="col-xs-12">

        <div class="table-responsive">
            <div class="dataTables_wrapper">   
				<div class="row">
					<div class="col-sm-12">
					    <form method="get" action="">
                            <label class="col-sm-4">
                            代理名称：<input type="text" class="input-text" name="agentname" value="<?php echo $agentname?>" />
                            </label>
                            <label class="col-sm-7">
                                <button class="btn btn-sm btn-primary" type="submit">
                                   <i class="icon-search"></i>搜索
                                </button>
                            </label>
						    <label class="col-sm-1">
							    <a class="btn btn-sm btn-primary" href="<?php echo admin_base_url('agent/agent/info')?>">
	                                <i class="icon-plus"></i>添加
	                            </a>
						    </label>
<!--
						    <label class="col-sm-1">
							    <button class="btn btn-sm btn-primary" id="del">
	                                <i class="icon-trash"></i>删除
	                            </button>
						    </label>
-->
                        </form>  
					</div>
				</div>
				<table class="table table-striped table-bordered table-hover dataTable">
				    <colgroup>
				       <col width="5%">
				       <col width="10%">
				       <col width="10%">
				       <col width="10%">
				       <col width="10%">
				       <col width="10%">
				       <col width="10%">
				       <col width="7%">
				       <col width="10%">
				       <col width="10%">
				       <col width="5%">
				    </colgroup>
	                <thead>
	                    <tr>
                            <th>序号</th>
                            <th>代理编号</th>
                            <th >代理名称</th>
                            <th>代理简称</th>
                            <th>联系人</th>
                            <th>手机号</th>
                          <!--  <th>QQ号码</th> -->
                            <th>状态</th>
                            <th>创建时间</th>
														<th>更新时间</th>
                            <th>重置密码</th>
	                    </tr>
	                </thead>
	
	                <tbody id="tbody_content">
                    
                     <?php

    if($lc_list){?>
                          <?php foreach($lc_list as $key =>$item):?>
                          <tr>
                            <td><?php echo ($key+1)+$page ;?></td>
                            <td><a href="<?php echo admin_base_url('agent/agent/show?id='.$item->agentid);?>"><?php echo $item->agentid;?></a></td>
                            <td><?php echo $item->agentname;?></td>
                            <td><?php echo $item->shortname;?></td>
                            <td><?php echo $item->contact;?></td>
                            <td><?php echo $item->mobilenum;?></td>
                      <!--      <td><?php echo $item->qq;?></td> -->
														<td class="hidden-480">
														   <label>
                                    <input type="checkbox" class="ace ace-switch ace-switch-6" name="status[]" item_id="<?php echo $item->agentid?>" value="<?php echo $item->status=='OFF'?'0':'1';?>" <?php echo $item->status=='OFF'?'':'checked';?> >
                                    <span class="lbl"></span>
                                </label>
                                                        </td>
                            <td><?php echo $item->createtime;?></td>
                            <td><?php echo $item->updatetime;?></td>
                            <td><div class="visible-md visible-lg hidden-sm hidden-xs action-buttons"><a href="<?php echo admin_base_url('agent/agent/modify_pwd?id='.$item->agentid);?>"><i class="icon-undo bigger-130"></i></a></div></td>
                          </tr>
                          <?php endforeach;?>
                     <?php }else{?>
														<tr><td colspan="9" style="text-align:center;">未找到数据</td></tr>
 											<?php	}?>
	                </tbody>
	            </table>
	            
			    <?php $this->load->view('admin/common/page')?>
            </div>
        </div>
    </div>
</div>
