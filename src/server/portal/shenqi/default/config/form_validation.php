<?php
$config = array(
        'user/server/index/subscribe'=>array(
                array(
                        'field' => 'keyword',
                        'label' => '业务名称',
                        'rules' => 'trim|min_length[2]'
                ),
        ),
        'admin/setting/basic/info'=>array(
                array(
                        'field' => 'site_admin_email',
                        'label' => '管理员邮箱',
                        'rules' => 'trim|valid_email'
                ),
                array(
                        'field' => 'site_admin_qq',
                        'label' => '管理员QQ',
                        'rules' => 'trim|numeric'
                ),
                array(
                        'field' => 'site_page_num',
                        'label' => '每页数量',
                        'rules' => 'trim|numeric'
                ),
                array(
                        'field' => 'site_accesslogin_count',
                        'label' => '限制登录总共次数',
                        'rules' => 'trim|numeric'
                ),
                array(
                        'field' => 'site_accesslogin_time',
                        'label' => '拒绝登录等待时间',
                        'rules' => 'trim|numeric'
                ),
        ),
        'index/_reset_password' => array(
                array(
                        'field' => 'password',
                        'label' => '新密码',
                        'rules' => 'trim|required|alpha_numeric|matches[confirm_password]|min_length[6]|max_length[20]'
                ),
                array(
                        'field' => 'confirm_password',
                        'label' => '确认密码',
                        'rules' => 'trim|required'
                )
        ),
        'index/_update_password' => array(
                array(
                        'field' => 'old_password',
                        'label' => '旧密码',
                        'rules' => 'trim|required|min_length[6]|max_length[20]'
                ),
                array(
                        'field' => 'password',
                        'label' => '新密码',
                        'rules' => 'trim|required|alpha_numeric|matches[confirm_password]|min_length[6]|max_length[20]'
                ),
                array(
                        'field' => 'confirm_password',
                        'label' => '确认密码',
                        'rules' => 'trim|required'
                )
        ),
        'admin/user/info/update'=>array(
                array(
                        'field' => 'nickname',
                        'label' => '昵称',
                        'rules' => 'trim|min_length[2]|max_length[100]|'
                ),
                array(
                        'field' => 'email',
                        'label' => '邮箱',
                        'rules' => 'trim|valid_email|max_length[50]'
                ),
        ),
        'user/index/_save_info' => array(
                array(
                        'field' => 'name',
                        'label' => '姓名',
                        'rules' => 'trim|max_length[32]|'
                ),
                array(
                        'field' => 'email',
                        'label' => '邮箱',
                        'rules' => 'trim|valid_email|max_length[50]'
                ),
                array(
                        'field' => 'adress',
                        'label' => '地址',
                        'rules' => 'trim|max_length[200]'
                ),
        ),
       'user_name_passport' => array(
                array(
                        'field' => 'name',
                        'label' => '姓名',
                        'rules' => 'trim|min_length[2]|max_length[30]|'
                ),
                array(
                        'field' => 'passportNo',
                        'label' => '证件号',
                        'rules' => 'trim|required|min_length[10]|max_length[30]|alpha_numeric'
                ),
        ),
);
