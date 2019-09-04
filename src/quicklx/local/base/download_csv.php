<?php
extract($_POST);
$dataToDownload = json_decode($csvdata);
$name = $name;

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$name.'.csv');
// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');



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
