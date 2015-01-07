<div class="row">
					<div class="col-sm-12">
					<label class="col-sm-2">
					<button class="btn btn-sm btn-primary" type="submit">
					<i class="icon-search"></i>添加组
					</button>
					</label>
					<label class="col-sm-2">
					<button class="btn btn-sm btn-primary" type="submit">
					<i class="icon-search"></i>删除组
					</button>
					</label>
					<label class="col-sm-2">
					<button class="btn btn-sm btn-primary" type="submit">
					<i class="icon-search"></i>添加组成员
					</button>
						    </label>
						    <label class="col-sm-2">
							    <button class="btn btn-sm btn-primary" id="del">
	                                <i class="icon-trash"></i>删除组成员
	                            </button>
						    </label>
						    <label class="col-sm-2">
							    <button class="btn btn-sm btn-primary" id="del">
	                                <i class="icon-trash"></i>批量生成
	                            </button>
						    </label>
					</div>
				</div>
<div class="row">
		<div class="col-xs-12 col-sm-3 widget-container-span ui-sortable" style="padding-right:2px">
										<div class="widget-box">
											<div class="widget-header">
												<h5 class="smaller">分组</h5>
											</div>

											<div class="widget-body">
												<div class="widget-main padding-6">
												<ul class="nav nav-list" id="groups">
                        	      <li class="">
																<a>
																<div class="col-xs-12 col-sm-1"></div>
                                <span class="menu-text"> 控制台</span>
																</a></li>
												</div>
											</div>
										</div>
									</div>
<div class="col-xs-12 col-sm-9 widget-container-span ui-sortable" style="padding-left:2px">
										<div class="widget-box">
											<div class="widget-header">
												<h5 class="smaller">组成员</h5>
											</div>

											<div class="widget-body">
												<div class="widget-main padding-6">
<table class="table table-striped table-bordered table-hover">
														<thead class="thin-border-bottom">
															<tr>

															<th class="center">
															<label>
																<input type="checkbox" class="ace">
																<span class="lbl"></span>
															</label>
														</th>																<th>
																	<i class="icon-user"></i>
																	User
																</th>

																<th>
																	<i>@</i>
																	Email
																</th>
																<th class="hidden-480">Status</th>
															</tr>
														</thead>

														<tbody>
															<tr>
															<td class="center">
															<label>
																<input type="checkbox" class="ace">
																<span class="lbl"></span>
															</label>
														</td>
																<td class="">Alex</td>

																<td>
																	<a href="#">alex@email.com</a>
																</td>

																<td class="hidden-480">
																	<span class="label label-warning">Pending</span>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
</div>
<script >
$("#addgroup").click(function (){
                                                                $.layer({
type: 2,
maxmin: true,
shadeClose: true,
title: '',
offset: ['20px',''],
area: ['800px', ($(window).height() - 50) +'px'],
iframe: {src: '<?php echo base_url('bp/businessManageBuf/spb');?>?id='+id+'&'+Math.random()},
close: function (i)
{
var c = layer.getChildFrame("#tbody_content tr").length;
if(layer.getChildFrame("#tbody_content tr")[0]){
$("#spbc").html(c+"条");
}
layer.close(i);
} 
})
});
</script>
