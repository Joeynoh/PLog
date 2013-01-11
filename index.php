<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>

<iframe src="example.log" width="100%" height="100%"></iframe>

<?php
	
	include('src/class.log.php');
	
	$log = new Log();
	
	$log->clearLog();
	
	$log->entry('Test');

	echo 'OK';

?>

</body>
</html>
