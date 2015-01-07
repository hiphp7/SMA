<?php
class Csv
{
	var $fileHandler;
	
	private function open($file)
	{
		$this->fileHandler =  fopen($file,'r');
		return $this->fileHandler;
	}
	private function close()
	{
		return fclose($this->fileHandler);
	}
	function readCsv($file)
	{
		$rt = array();
		$handle = $this->open($file);
		if ($handle) {
			while (($buffer = fgets($handle)) !== false) {
				if(!empty(trim($buffer))){
					$rt[] = explode(',',trim($buffer));
				}
			}
		}
		$this->close();
		return $rt;
	}
	
	function csv2array($file)
	{
		$cvs = $this->readCsv($file);
		$tmp = $cvs[0];
		$tmp[0] = $this->clearBOM($tmp[0]);
		$ret = array();
		for ($i = 1; $i < sizeof($cvs); $i++) {
			foreach($tmp as $k=> $v)
			{
				$ret[$i-1][$v] = $cvs[$i][$k];
			}
		}
		return $ret;
	}
	function array2csv(array $data)
	{
		$rt = array();
		foreach($data as $val)
		{
			if(is_array($val))
			{
				$rt[] = join($val,',');
				continue;
			}
			$rt[] = $val;
		}
		return join($rt,"\r\n");
	}
	
	function clearBOM($contents)
	{
		$charset[1]=substr($contents,0,1);
		$charset[2]=substr($contents,1,1);
		$charset[3]=substr($contents,2,1);
		if(ord($charset[1])==239 && ord($charset[2])==187 && ord($charset[3])==191){
			return substr($contents,3);
		}
		return $contents;
	}
}
?>