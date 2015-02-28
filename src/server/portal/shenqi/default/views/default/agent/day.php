<div class="row">
    <div class="col-xs-12">

        <div class="table-responsive">
            <div class="dataTables_wrapper">   
				<div class="row">
					<div class="col-sm-12">
					    <form method="get" action="">
                            <div class="input-group col-sm-5">
                             <label for="date_range">
                             时间段：
                             </label>
								<span class="input-group-addon">
									<i class="icon-calendar bigger-110"></i>
								</span>
<input type="text" id='date_range' name="date_range" value="<?php echo $date_range;?>" class="form-control" style="margin-left: 0; border-left: none;"/>
</div>
                             <label class="col-sm-4">
                                <button class="btn btn-sm btn-primary" type="submit">
                                   <i class="icon-search"></i>搜索
                                </button>
                            </label>
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
                            <td><?php echo $item->s_date;?></td>
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
<link rel="stylesheet" href="<?php echo static_url('theme/ace/css/daterangepicker.css')?>" />
<script src="<?php echo static_url('theme/ace/js/date-time/moment.min.js')?>"></script>
<script src="<?php echo static_url('theme/ace/js/date-time/daterangepicker.min.js')?>"></script>
 <script>
 $(function (){
		$('#date_range').daterangepicker().prev().on(ace.click_event, function(){
			$(this).next().focus();
		});
 });
 </script>
