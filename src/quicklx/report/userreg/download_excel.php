<?php

require('../../config.php');
require_once('extend_excellib.php');

extract($_POST);

$workbook = new UsersExcelWorkbook('userreport.xlsx', 'excel2007');
$worksheet = array();
$worksheet = $workbook->add_worksheet('usersreport');

$worksheet->get_excel()->fromArray(json_decode($exceldata), NULL, 'A1');

ob_clean();

$workbook->close();
exit();
?>

