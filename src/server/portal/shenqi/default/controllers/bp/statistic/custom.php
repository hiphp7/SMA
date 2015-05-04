<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Custom extends Admin_Controller { 

				var $sellerid;

				function __construct()  {  
								parent::__construct(); 
								$this->load->model(array('sharepage','issue_model'));
								$this->sellerid = $this->user_info->sellerid;
				}  

				function index(){
								$per_page = (int)$this->input->get_post('per_page');
								$cupage = config_item('site_page_num'); //每页显示个数
								$return_arr = array ('total_rows' => true );
								$option =array('where'=>array('t_issue.sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
								$date_range = $this->input->get_post('date_range');
								$string=base_url('bp/statistic/custom?');
								if($date_range){
												$tmp_date = explode('-', $date_range);
												$option['where']['visitTime >= '] = trim($tmp_date[0]).' 00:00:00';
												$option['where']['visitTime <= '] = trim($tmp_date[1]).' 23:59:59';
												$string .= '&date_range='.$date_range;
								}else{
												$option['where']['visitTime >= '] = date('Y/m/d').' 00:00:00';
												$option['where']['visitTime <= '] = date('Y/m/d').' 23:59:59';
												$date_range = date('Y/m/d').' - '.date('Y/m/d');
												$string .= '&date_range='.$date_range;
								}
								$option['select'] ='c.title,v.*,starttime,endtime';
								$option['join'] = array(array('t_customer_visit v','v.issueId=t_issue.issueId'),array('t_content c','c.contentid=t_issue.contentid'));
								$option['order'] = 'visitTime desc,v.issueId';
								$rt =$this->issue_model->getAll($option,$return_arr);
								$page = $this->sharepage->showPage ($string, $return_arr ['total_rows'], $cupage );
								$this->_template('bp/statistic/custom',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'date_range'=>$date_range));
				}	
				function toXls2()
				{
								$this->load->library('toxls');
								$title = array('访问日期','访问号码','发布号','发布标题','任务机');
								$filename='custom'.time();
								$this->toxls->head($filename);
								$rowno=0;
								$this->toxls->xlsBOF();
								$this->toxls->xlsRow($title,$rowno);
								$option =array('where'=>array('t_issue.sellerId'=>$this->sellerid));
								$date_range = $this->input->get_post('date_range');
								if($date_range){
												$tmp_date = explode('-', $date_range);
												$option['where']['visitTime >= '] = trim($tmp_date[0]).' 00:00:00';
												$option['where']['visitTime <= '] = trim($tmp_date[1]).' 23:59:59';
								}else{
												$option['where']['visitTime >= '] = date('Y/m/d').' 00:00:00';
												$option['where']['visitTime <= '] = date('Y/m/d').' 23:59:59';
								}
								$option['select'] ='v.visittime,v.customermobile,v.issueid,c.title,v.roujimobile';
								$option['join'] = array(array('t_customer_visit v','v.issueId=t_issue.issueId'),array('t_content c','c.contentid=t_issue.contentid'));
								$option['order'] = 'visitTime desc,v.issueId';
								$list =$this->issue_model->getAll2Array($option);
								if(!empty($list)){
												foreach($list as $key => $row){
																$rowno++;
																$this->toxls->xlsRow($row,$rowno);
												}
								}
								$this->toxls->xlsEOF();
				}
				function toXls()
				{
								$this->load->library('PHPExcel');
								$m_objPHPExcel = new PHPExcel();
								$objWriter = PHPExcel_IOFactory::createWriter($m_objPHPExcel, 'Excel2007');
								// 设置基本属性
								$m_objPHPExcel->getProperties()->setCreator("guangzhou weike")
												->setLastModifiedBy("guangzhou weike")
												->setTitle("Microsoft Office Excel Document")
												->setSubject("Test Data Report -- From guangzhou weike")
												->setDescription("LD Test Data Report, Generate by guangzhou weike");

								// 创建多个工作薄
								$sheet1 = $m_objPHPExcel->createSheet();
								// 设置第一个工作簿为活动工作簿
								$m_objPHPExcel->setActiveSheetIndex(0);
								$m_objPHPExcel->getActiveSheet()->setTitle( '客户访问名单');
								// 设置默认字体和大小
								$m_objPHPExcel->getDefaultStyle()->getFont()->setName( '宋体');
								$m_objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
								// 设置一列的宽度
								$m_objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
								// 设置一行的高度
								$m_objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(30);
								// 合并单元格
								//$m_objPHPExcel->getActiveSheet()->mergeCells('A1:P1');

								// 给特定单元格中写入内容
								$title = array('A'=>'访问日期','B'=>'访问号码','C'=>'发布号','D'=>'发布标题','E'=>'任务机');
								$rowKey = array('A'=>'visittime','B'=>'customermobile','C'=>'issueid','D'=>'title','E'=>'roujimobile');
								$rowno=1;
								foreach($title as $k =>$v)
								{
												$m_objPHPExcel->getActiveSheet()->setCellValue($k.'1',$v);
								}
								$filename='custom'.time().'.xlsx';
								$option =array('where'=>array('t_issue.sellerId'=>$this->sellerid));
								$date_range = $this->input->get_post('date_range');
								if($date_range){
												$tmp_date = explode('-', $date_range);
												$option['where']['visitTime >= '] = trim($tmp_date[0]).' 00:00:00';
												$option['where']['visitTime <= '] = trim($tmp_date[1]).' 23:59:59';
								}else{
												$option['where']['visitTime >= '] = date('Y/m/d').' 00:00:00';
												$option['where']['visitTime <= '] = date('Y/m/d').' 23:59:59';
								}
								$option['select'] ='v.visittime,v.customermobile,v.issueid,c.title,v.roujimobile';
								$option['join'] = array(array('t_customer_visit v','v.issueId=t_issue.issueId'),array('t_content c','c.contentid=t_issue.contentid'));
								$option['order'] = 'visitTime desc,v.issueId';
								$list =$this->issue_model->getAll2Array($option);
								if(!empty($list)){
												foreach($list as $key => $row){
																$rowno++;
																foreach($rowKey as $k =>$v)
																{
																				$m_objPHPExcel->getActiveSheet()->setCellValue($k.$rowno,$row[$v]);
																}
												}
								}
								header("Pragma: public");
								header("Expires: 0");
								header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
								header("Content-Type:application/force-download");
								header("Content-Type: application/vnd.ms-excel;");
								header("Content-Type:application/octet-stream");
								header("Content-Type:application/download");
								header("Content-Disposition:attachment;filename=".$filename);
								header("Content-Transfer-Encoding:binary");
								$objWriter->save("php://output"); 
				}
} 
