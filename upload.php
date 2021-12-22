<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: x-requested-with, x-file-name, x-index, x-total, x-hash, Content-Type, origin, authorization, accept, client-security-token");
/*
print_r($_SERVER);
exit();
*/
$dir = realpath('./uploads');

if (!isset($_SERVER['HTTP_X_FILE_NAME']))
    throw new Exception('Name required');
if (!isset($_SERVER['HTTP_X_INDEX']))
    throw new Exception('Index required');
if (!isset($_SERVER['HTTP_X_TOTAL']))
    throw new Exception('Total chunks required');

if(!preg_match('/^[0-9]+$/', $_SERVER['HTTP_X_INDEX']))
    throw new Exception('Index error');
if(!preg_match('/^[0-9]+$/', $_SERVER['HTTP_X_TOTAL']))
    throw new Exception('Total error');
 
$filename   = $_SERVER['HTTP_X_FILE_NAME'];
//$filesize   = $_SERVER['HTTP_X_FILE_SIZE'];
$index      = intval($_SERVER['HTTP_X_INDEX']);
$total      = intval($_SERVER['HTTP_X_TOTAL']);
$hash      = $_SERVER['HTTP_X_HASH'];


// save the part to a file and extract the md5a
$target = $dir."/".$filename."-".$index."-".$total;

$input = fopen("php://input", "r");
file_put_contents($target, $input);
$input = file_get_contents($target);
$hash_file = md5($input);

/*
// errorie proboka
$r = rand(0,1);
if($r>0)
	$hash_file = '1234';
*/

// if the hashes are the same the ascent is well done
if($hash===$hash_file)
{
	$result = array
	(
		'filename' => $filename,
		'start' => $index,
		'end' => $total,
		'percent' => intval(($index+1) * 100 / $total),
		'hash' => $hash_file
	);
	
	// lehengo zatiekin bat egingo dugu eta izen berria jarri
	if($index>0)
	{
		$target_old = $dir."/".$filename."-".($index-1)."-".$total;
		// goidxen ezan doten moduen ostantzien $input-egaz eztabil, zeitzik? Idearik pez
		file_put_contents($target_old, $input, FILE_APPEND);
		rename($target_old, $target);	
	}
	// azkena da
	if($index===intval($total-1))
	{
		$result['percent'] = 100;
		rename($target, $dir."/".$filename);
	}
}
else
{
	$result = array
	(
		'error' => 'E_HASH'
	);
}
echo json_encode($result);
