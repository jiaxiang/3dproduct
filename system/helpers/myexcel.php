<?php defined('SYSPATH') or die('No direct script access.');
/*
 * excel 操作类
 */
class myexcel_Core {
    private static $instance = NULL;
    // 获取单态实例 
    public static function get_instance()
    {
        if(self::$instance === NULL)
        {
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }    
    
    
    /*
     * 读取Excel信息并以数组返回
     */
    public function get_rows_from_excel($filepath,&$infos)
    {
        if(empty($filepath))
            return  $infos;
        
        $php_reader = new PHPExcel_Reader_Excel2007();   
        
        if(!$php_reader->canRead($filepath))
        {
            $php_reader = new PHPExcel_Reader_Excel5();   
            if(!$php_reader->canRead($filepath))
            {        
               return  $infos;
            }
        }
        
        $cur_excel = $php_reader->load($filepath);
        $cur_sheet = $cur_excel->getSheet(0);
        
        $all_column = $cur_sheet->getHighestColumn();        //取得一共有多少列
        $all_row = $cur_sheet->getHighestRow();              //取得一共有多少行

        $infos_have = array();
        $infos_error = array();
        $i = 0;
        $j = 0;
        
        
        for($cur_row = 1; $cur_row <= $all_row ;$cur_row++)    //读取信息入数组
        {
            $flagadd = FALSE;
            $tmprow = array();
            $tmpchar = '';

            for ($cur_column='A';$cur_column <= $all_column;$cur_column++)
            {   
                
                $address = $cur_column.$cur_row;
                $curinfo = trim($cur_excel->getActiveSheet()->getCell($address)->getvalue());
                
                //if($cur_column == 'A')
                //{
                    if(!empty($curinfo)) 
                    {
                        $flagadd = TRUE;
                    }
                //}
                $tmprow[] = $curinfo;
                $tmpchar .= $curinfo;
            }

            if($flagadd)
            {
                $infos['infos_have'][$i] = $tmprow;
                $i++;
            } elseif(!empty($tmpchar)) {
                $infos['infos_error'][$j] = $tmprow;
                $j++;
            }

        }
    }
    
    
    /*
     * 根据数组生成excel,返回生成的文件名
     * 
     */
    public function get_excel_from_rows(&$infos, $title = '')
    {
        if(empty($infos))
            return FALSE;
                    
        //获取原有数据值
        $fetch_one = $infos[0]; 
        $all_column = count($fetch_one);
        $all_row = count($infos);
                
        if($all_column == 0) 
            return FALSE;
        
        $column_end = 'A';
        for ($i = 1 ; $i < $all_column ; $i++) 
        {
            $column_end++;
        }
                        
        $objExcel = new PHPExcel();
        //设置文档基本属性  
        $objProps = $objExcel->getProperties();  
        $objProps->setCreator("MyOs");  
        $objProps->setLastModifiedBy("MyOs");
        $objProps->setTitle("Download Excel");  
        $objProps->setSubject("Download Excel - $title");
        $objProps->setDescription("");
        $objProps->setKeywords("");
        $objProps->setCategory("");

        //*************************************  
        //设置当前的sheet索引，用于后续的内容操作。  
        //一般只有在使用多个sheet的时候才需要显示调用。  
        //缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0  
        $objExcel->setActiveSheetIndex(0);  
        $objActSheet = $objExcel->getActiveSheet();  
        
        //设置当前活动sheet的名称
        $objActSheet->setTitle($title);

        $i = 1;
        foreach ($infos as $row) 
        {
            $j = 0;
            for($column_i = 'A'; $column_i <= $column_end; $column_i++)
            {   
                $objActSheet->setCellValue($column_i.$i, $row[$j]);
                $j++;
            }
            $i++;
        }
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
                
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.date('YmdHis').'.xls');
        header("Content-Transfer-Encoding: binary");
        //header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
    
}
