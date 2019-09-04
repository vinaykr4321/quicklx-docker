<?php
extract($_POST);
$dataToDownload = json_decode($csvdata);
//array_shift($dataToDownload);

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');
// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
//fputcsv($output, array('User Name','Email','First Name','Last Name','Organization','User created','First access','Last access'));


foreach ($dataToDownload as $value) 
{
	foreach ($value as $v) 
	{
		if (empty($v)) 
		{
			$new[] = ' ';
		}
		else
		{
			$new[] = $v;
		}
	}
	fputcsv($output, $new);
	unset($new);
}

?>