<div class="row">
    <div class="col-xs-12">

        <div class="table-responsive">
            <div class="dataTables_wrapper">   
				<div class="row">
					<div class="col-sm-12">
					    <form method="get" action="">
                            <label class="col-sm-4">
                            标题：<input type="text" class="input-text" name="title" value="<?php echo $title?>" />
                            </label>
                            <label class="col-sm-7">
                                <button class="btn btn-sm btn-primary" type="submit">
                                   <i class="icon-search"></i>搜索
                                </button>
                            </label>
						    <label class="col-sm-1">
							    <a class="btn btn-sm btn-primary" href="<?php echo base_url('bp/contentManage/contentMake/info')?>">
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
				       <col width="5%">
				    </colgroup>
	                <thead>
	                    <tr>
                            <th>序号</th>
                            <th >标题</th>
                            <th>制作时间</th>
                            <th>操作</th>
	                    </tr>
	                </thead>
	
	                <tbody id="tbody_content">
                    
                     <?php

    if($lc_list){?>
                          <?php foreach($lc_list as $key =>$item):?>
                          <tr>
                            <td><?php echo ($key+1)+$page ;?></td>
                            <td><a href="<?php echo base_url('bp/contentManage/contentMake/show?id='.$item->contentid);?>" ><?php echo $item->title;?></td>
                            <td><?php echo $item->createtime;?></td>
                            <td>
														<div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
	                                <a class="red tooltip-success" href="javascript:" onclick="msgdelete(<?php echo $item->contentid?>)" data-rel="tooltip" title="删除">
	                                    <i class="icon-trash bigger-130"></i>
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
