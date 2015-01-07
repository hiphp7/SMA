<div class="row">
    <div class="col-xs-12">

        <div class="table-responsive">
            <div class="dataTables_wrapper">   
				<div class="row">
					<div class="col-sm-12">
					    <form method="get" action="">
                        </form>  
					</div>
				</div>
				<table class="table table-striped table-bordered table-hover dataTable">
				    <colgroup>
				       <col width="15%">
				       <col width="15%">
				       <col width="15%">
				       <col width="15%">
				    </colgroup>
	                <thead>
	                    <tr>
                            <th>月份</th>
                            <th >代理/商户</th>
                            <th >类型</th>
                            <th>发送量</th>
	                    </tr>
	                </thead>
	
	                <tbody id="tbody_content">
                    
                     <?php

    if($lc_list){?>
                          <?php foreach($lc_list as $key =>$item):?>
                          <tr>
                            <td><?php echo $item->s_month;?></td>
                            <td><?php echo $item->agentname;?></td>
                            <td><?php echo $item->type;?></td>
                            <td><?php echo $item->sentcount;?></td>
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
