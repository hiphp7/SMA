<div class="row">
    <div class="col-xs-12">
        <div class="table-header">
            &nbsp;
        </div>

        <div class="table-responsive">
            <div class="dataTables_wrapper">   
				<div class="row">
					<div class="col-sm-12">
					    <form method="get" action="">
						    <label>
							    <a class="btn btn-sm btn-primary" href="<?php echo admin_base_url('user/index/info')?>">
	                                <i class="icon-plus"></i>添加
	                            </a>
						    </label>
                            <label>
                             昵称：<input type="text"  name="nickname" value="" />
                            </label>
                             <label>
                             账号：<input type="text"  name="user_name" value="" />
                            </label>
                            <label>
                                <button class="btn btn-sm btn-primary" type="submit">
                                   <i class="icon-search"></i>搜索
                                </button>
                            </label>
                        </form>  
					</div>
				</div>
				<table class="table table-striped table-bordered table-hover dataTable">
				    <colgroup>
				       <col width="3%">
				       <col width="15%">
				       <col width="15%">
				       <col width="15%">
				       <col width="8%">
				       <col>
                       <col width="15%">
				    </colgroup>
	                <thead>
	                    <tr>
	                        <th style="text-align: center;">ID</th>
                            <th>账号</th>
                            <th>昵称</th>
                            <th>用户组</th>
                            <th class="hidden-480">状态</th>
                            <th>注册时间</th>
                            <th>操作</th>
	                    </tr>
	                </thead>
	
	                <tbody id="tbody_content">
                    
                    <?php if($lc_list):?>
                      <?php foreach($lc_list as $item):?>
                      <tr>
                        <td style="text-align: center;"><?php echo $item->uid;?></td>
                        <td><a href="<?php echo admin_base_url('user/index/edit?uid=')?><?php echo $item->uid;?>" ><?php echo $item->user_name;?></a></td>
                        <td><?php echo $item->nickname;?></td>
                        <td><?php if(!$item->gid):?>超级管理员<?php else:?><?php if($item->group_name):?><?php echo $item->group_name;?><?php else:?>无<?php endif;?><?php endif;?></td>
                        
                        <td class="hidden-480">
                                <label>
                                    <input type="checkbox" class="ace ace-switch ace-switch-6" name="status[]" item_id="<?php echo $item->uid;?>" value="<?php echo $item->status;?>" <?php if($item->status):?>checked="checked"<?php endif;?> >
                                    <span class="lbl"></span>
                                </label>
	                    </td>

                        <td><?php echo datetime($item->regtime);?></td>
                        
                        <td>
	                            <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
	                                <?php if($item->status > 0):?> 
	                                <a class="green <?php if($item->status < 0):?>disabled<?php endif;?>" href="<?php echo admin_base_url('user/index/edit?uid=')?><?php echo $item->uid;?>">
	                                    <i class="icon-pencil bigger-130"></i>
	                                </a>
	                                <a class="red" href="javascript:" onclick="msgdelete(<?php echo $item->uid;?>)" >
	                                    <i class="icon-trash bigger-130"></i>
	                                </a>
	                                <?php else:?>
	                                <button class="btn btn-xs btn-danger disabled"><i class="icon-trash bigger-130"></i> 已停用</button>
	                                <?php endif;?>
	                            </div>
	
	                            <div class="visible-xs visible-sm hidden-md hidden-lg">
	                                <div class="inline position-relative">
	                                    <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
	                                        <i class="icon-caret-down icon-only bigger-120"></i>
	                                    </button>
	
	                                    <ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
	
	                                        <li>
	                                            <a href="<?php echo admin_base_url('user/index/edit?uid=')?><?php echo $item->uid;?>" class="tooltip-success" data-rel="tooltip" title="修改">
	                                                <span class="green">
	                                                    <i class="icon-edit bigger-120"></i>
	                                                </span>
	                                            </a>
	                                        </li>
	
	                                        <li>
	                                            <a href="javascript:" onclick="msgdelete(<?php echo $item->uid;?>)" class="tooltip-error" data-rel="tooltip" title="删除">
	                                                <span class="red">
	                                                    <i class="icon-trash bigger-120"></i>
	                                                </span>
	                                            </a>
	                                        </li>
	                                    </ul>
	                                </div>
	                            </div>
	                        </td>

                      </tr>
                      <?php endforeach;?>
                    <?php else:?>
											<tr><td colspan="10" align="center">暂无内容</td></tr>
                    <?php endif;?>

	                </tbody>
	            </table>
	            
			    <?php $this->load->view('admin/common/page')?>
            </div>
        </div>
    </div>
</div>
