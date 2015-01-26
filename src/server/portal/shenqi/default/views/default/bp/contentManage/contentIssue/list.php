<div class="row">
    <div class="col-xs-12">

        <div class="table-responsive">
            <div class="dataTables_wrapper">   
				<div class="row">
					<div class="col-sm-12">
					    <form method="get" action="">
                            <label class="col-sm-4">
                            发布号：<input type="text" class="input-text" name="issueid" value="<?php echo $issueid?>" />
                            </label>
                            <label class="col-sm-4">
                            标题：<input type="text" class="input-text" name="title" value="<?php echo $title?>" />
                            </label>
                            <label class="col-sm-3">
                                <button class="btn btn-sm btn-primary" type="submit">
                                   <i class="icon-search"></i>搜索
                                </button>
                            </label>
						    <label class="col-sm-1">
							    <a class="btn btn-sm btn-primary" href="<?php echo base_url('bp/contentManage/contentIssue/info')?>">
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
				       <col width="3%">
				       <col width="10%">
				       <col width="10%">
				       <col width="10%">
				       <col width="10%">
				       <col width="10%">
				       <col width="5%">
				    </colgroup>
	                <thead>
	                    <tr>
                            <th>序号</th>
                            <th>发布号</th>
                            <th >标题</th>
                            <th>开始时间</th>
                            <th>结束时间</th>
                            <th>群发数量</th>
                            <th>操作</th>
	                    </tr>
	                </thead>
	
	                <tbody id="tbody_content">
                    
                     <?php

    if($lc_list){?>
                          <?php foreach($lc_list as $key =>$item):?>
                          <tr>
                            <td><?php echo ($key+1)+$page ;?></td>
                            <td><a href="<?php echo base_url('bp/contentManage/contentIssue/info?id='.$item->issueid) ?>"><?php echo $item->issueid;?></a></td>
                            <td><?php echo $item->title;?></td>
                            <td><?php echo substr($item->starttime,0,10);?></td>
                            <td><?php echo substr($item->endtime,0,10);?></td>
                            <td><?php echo $item->target;?></td>
                            <td>
														<div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
	                                <a class="red tooltip-success" href="javascript:" onclick="revoke(<?php echo $item->issueid?>)" data-rel="tooltip" title="撤销">
	                                    <i class="icon-undo bigger-130"></i>
	                                </a>
	                                <a class="red tooltip-success" href="<?php echo base_url('bp/contentManage/contentIssue/edit?id='.$item->issueid);?>" data-rel="tooltip" title="修改">
	                                    <i class="icon-edit bigger-130"></i>
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
function revoke(id){
	if(!id){
		layer.alert("此条数据不存在,请刷新后在试",3); 
		return false;
	}
	
	layer.confirm('是否撤销？',function(index){
		var url = current_url + "/revoke";

        var loading = layer.load('请稍后...');
		jQuery.post(url,{id:id},function(data){
			var obj = eval("("+data+")");
			if(obj.status == 1){
				layer.msg(obj.msg,1,1); 
				window.location.reload(); 
			}else{
				layer.alert(obj.msg,3);
			}
		}).always(function () {
          	layer.close(loading);
        });

        layer.close(index);
    });
    return false;
}
</script>
