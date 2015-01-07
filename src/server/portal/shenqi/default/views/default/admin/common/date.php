<!--date S-->

<script type="text/javascript" src="<?php echo static_url('theme/common/My97DatePicker/WdatePicker.js')?>?<?php echo random(3)?>"></script>
<script>
$(function(){
    $(document).on('click','.Wdate',function(){
        var o = $(this);
        if(o.attr('dateFmt') != '')
            WdatePicker({dateFmt:o.attr('dateFmt'),maxDate:'%y-%M-%d'});
        else if(o.hasClass('month'))
            WdatePicker({dateFmt:'yyyy-MM',maxDate:'%y-%M-%d'});
        else if(o.hasClass('year'))
            WdatePicker({dateFmt:'yyyy',maxDate:'%y-%M-%d'});
        else 
            WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'%y-%M-%d'});
    });
});
</script>
<!--date E-->