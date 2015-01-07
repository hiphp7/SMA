<?php 
    echo ace_header(array_values($this->breadcrumb));

    echo ace_form_open('','',array('cid'=>$item['cid']));
    
?>
    <div class="row">
        <div class="col-xs-8">
            <?php 

            $data = array('label_text'=>'栏目类别','help'=>'栏目的类别');
            echo ace_dropdown($data,'type',$this->model->type,$item['type']);
            
            $data = array('label_text'=>'上级栏目','help'=>'可选,选择了此栏目将为下级栏目');
            echo ace_dropdown($data,'parents',$parents,$item['parents']);
            
            echo ace_input_m('栏目标题','title',$item['title'],'maxlength="50"');
            
            echo ace_input(array('label_text'=>'目录名','help'=>'最后面请加上“/”'),'directory',$item['directory'],'maxlength="100"');
            
            echo ace_input('控制器名','con_name',$item['con_name'],'maxlength="100"');
            
            ?>
            
	       <!--/内容 -->
        </div>
        <div class="col-xs-12 col-sm-4">
            <div class="widget-box">
                <div class="widget-header">
                    <h4>参数</h4>

                    <div class="widget-toolbar">
                        <a data-action="collapse" href="#">
                            <i class="icon-chevron-up"></i>
                        </a>
                    </div>
                </div>

                <div class="widget-body">
                    <div class="widget-body-inner" style="display: block;">
                       <div class="widget-main">
	                        <div>
	                            <label for="form-field-8">样式名</label>
	                            <input id="form-field-8" class="form-control" name="params[class_sfx]" maxlength="30" value="<?php echo $item['params']->get('class_sfx')?>" />
	                        </div>
	                    </div>
	                </div>
                </div>
            </div>
        </div>
    </div>
<?php 
        echo ace_srbtn('admin/setting/colum?type='.$item['type']);      
      
    echo ace_form_close();
?>
