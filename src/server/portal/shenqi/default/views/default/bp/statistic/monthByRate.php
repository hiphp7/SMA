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
                            <th>日期</th>
                            <th >日发送量</th>
                            <th>日访问量</th>
                            <th>日转化率</th>
	                    </tr>
	                </thead>
	
	                <tbody id="tbody_content">
                    
                     <?php

    if($lc_list){?>
                          <?php foreach($lc_list as $key =>$item):?>
                          <tr>
                            <td><?php echo $item->s_month;?></td>
                            <td><?php echo $item->sentcount;?></td>
                            <td><?php echo $item->visitcount;?></td>
                            <td><?php echo $item->rate;?></td>
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
