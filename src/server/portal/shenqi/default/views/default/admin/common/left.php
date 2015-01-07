                <div class="sidebar" id="sidebar">
                    <script type="text/javascript">
                        try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
                    </script>

                    <ul class="nav nav-list">
                        <?php 
                        //一级菜单
                        foreach($this->menu_tree as $item):
                            $active = ($this->current_menu == $item['cid'] || array_key_exists($item['cid'],$this->breadcrumb)) ? 'active' : '';
                            $open = array_key_exists($item['cid'],$this->breadcrumb) ? 'open' : '';
                        ?>
	                    <li class="<?php echo $active.' '.$open?>">
	                        <a <?php if($item['child']):?> href="javascript:" class="dropdown-toggle" <?php else:?> href="<?php echo admin_base_url($item['con_name'],$item['directory'])?>" <?php endif;?>>
                                <i class="<?php echo $item['params']->get('class_sfx','')?>"></i>
                                <span class="menu-text"> <?php echo $item['title']?></span>
                                
								<?php if ($item['child']):?>
                                <b class="arrow icon-angle-down"></b>
                                <?php endif;?>
                            </a>
	                        <?php 
	                        //二级菜单
	                        if ($item['child']):?>
	                        <ul class="submenu">
	                            <?php foreach ($item['child'] as $child):?>
                                <li <?php if (array_key_exists($child['cid'],$this->breadcrumb)):?> class="active"<?php endif;?>>
                                    <a href="<?php echo admin_base_url($child['con_name'],$child['directory'])?>">
                                        <i class="icon-double-angle-right"></i>
                                        <?php echo $child['title']?>
                                    </a>
                                </li>
	                            <?php endforeach;?>
                            </ul>
	                        <?php endif;
	                        //二级菜单结束
	                        ?>
	                    </li>
	                    <?php endforeach;
	                        //一级菜单结束
                        ?>
                    </ul><!-- /.nav-list -->

                    <div class="sidebar-collapse" id="sidebar-collapse">
                        <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
                    </div>

                    <script type="text/javascript">
                        try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
                    </script>
                </div>
                <div class="main-content">
                    <div class="breadcrumbs" id="breadcrumbs">
                        <script type="text/javascript">
                            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                        </script>

                        <ul class="breadcrumb">
                            <?php
                            $i = 0; 
                            $c = count($this->breadcrumb);
                            foreach ($this->breadcrumb as $item):
                            ?>
                            <li <?php if ($i==$c):?> class="active"<?php endif;?>>
                                <?php if (!$i):?>
							    <i class="icon-home home-icon"></i>
							    <?php endif;?>
                                <?php echo $item['title']?>
                            </li>
                            <?php 
                                $i++;
                            endforeach;
                            ?>
                        </ul><!-- .breadcrumb -->
<!--
                        <div class="nav-search" id="nav-search">
                            <form class="form-search">
                                <span class="input-icon">
                                    <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                                    <i class="icon-search nav-search-icon"></i>
                                </span>
                            </form>
                        </div>
-->
                        <!-- #nav-search -->
                    </div> 
                    <!--内容-->
                    <div class="page-content">
                        <?php 
                        echo show_flash_tips();
                        echo show_alert_danger();
                        ?>
                        <div class="row">
                           <div class="col-xs-12">