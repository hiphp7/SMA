<script type="text/javascript" src="<?php echo static_url('theme/common/My97DatePicker/WdatePicker.js')?>?<?php echo random(3)?>"></script>
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
				       <col width="5%">
				    </colgroup>
	                <thead>
	                    <tr>
                            <th>日期</th>
                            <th >任务机</th>
                            <th>发送量</th>
	                    </tr>
	                </thead>
	
	                <tbody id="tbody_content">
                    
                     <?php

    if($lc_list){?>
                          <?php foreach($lc_list as $key =>$item):?>
                          <tr>
                            <td><?php echo $item->s_date;?></td>
                            <td><?php echo $item->mobile;?></td>
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
