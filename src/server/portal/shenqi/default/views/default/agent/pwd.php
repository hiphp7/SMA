<link rel="stylesheet" href="<?php echo static_url('theme/admin/css/password.css')?>" />
<?php
echo ace_form_open();

$options = array(
        'label_text'    => '旧密码',
        'datatype'      => 's6-20',
        'help'          => '旧密码',
        'errormsg'      => '旧密码格式不正确，只允许6-20位字符'
);
echo ace_password($options, 'old_password');

$options = array(
        'label_text'    => '新密码',
        'datatype'      => 'pwd',
        'help'          => lang('pwd_help_msg'),
        'errormsg'      => lang('pwd_help_msg')
);
echo ace_password($options, array('id'=>'q_password1','name'=>'password'));

$options = array(
        'label_text' => '确认密码',
        'datatype' => 'pwd',
        'help' => lang('pwd_help_msg'),
        'errormsg' => lang('pwd_help_msg')
);
echo ace_password($options, array('id'=>'q_password2','name'=>'confirm_password'));

echo ace_srbtn('', false);

echo ace_form_close();
?>
<script>
    $(document).ready(function(){
        $(".col-sm-5").addClass('col-sm-4').removeClass('col-sm-5');
        var qd_html = '<div class="passwordStrength"><b>密码强度：</b> <span class="">弱</span><span class="">中</span><span class="last">强</span></div>';

        $("#q_password1,#q_password2").closest('span').after(qd_html);
    })
</script>
