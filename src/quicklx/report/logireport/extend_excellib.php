<?php

require_once($CFG->libdir . '/excellib.class.php');

defined('MOODLE_INTERNAL') || die();

class UsersExcelWorkbook extends MoodleExcelWorkbook {
  
    public function __construct($filename, $type = 'Excel2007') {
        parent::__construct($filename, $type);
    }

    public function add_worksheet($name = '') {
        return new UsersExcelWorksheet($name, $this->objPHPExcel);
    }

}


class UsersExcelWorksheet extends MoodleExcelWorksheet {
    
     public function __construct($name, PHPExcel $workbook) {
        parent::__construct($name,$workbook);
    }

    public function get_excel(){
        return $this->worksheet;
    }
}


?>


