<?php
// if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Toxls{

  function xlsBOF() {
     ?>
      <html>
      <head>
        <meta http-equiv="Content-type" content="text/html;charset=utf8" />
         <style id"Classeur1_16681_Styles"></style>
      </head>
      <body>
             <table x:str border=1 cellpadding=0 cellspacing=0 width=100% style="border-collapse: collapse">
      <?php
      return;
  }

  function xlsEOF() {
      ?>
             </table>
       </body>
       </html>
      <?php
      return;
  }


  function xlsRow($row,$rowNo){
    $count=sizeof($row);
    echo "<tr>";
    for($colNo=0;$colNo<$count;$colNo++){
        $field=array_shift($row);
        /*
        if(is_numeric($field))
        $this->xlsWriteNumber($rowNo,$colNo,$field);
        else
        $this->xlsWriteLabel($rowNo,$colNo,$field);
        */
        echo "<td nowrap>".$field."</td>";
    }
    echo "</tr>";
  }

  function head($fileName='export'){
    header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=$fileName.xls");
    ?>
    <html xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns="http://www.w3.org/TR/REC-html40">
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <?php
  }

 }
?>
