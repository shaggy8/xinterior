<?php

$id = $_POST['id'];
$ok = $_POST['ok'];
$h = $_POST['h'];

$now = time();

if (strlen($id)>0)
{
    $fp=fopen('node.res','a');
    fwrite($fp,"$now :: $id :: $ok :: $h\n");
    fclose($fp);

    if (file_exists("packs/$suff"))
    {
	$suff = substr($id,0,3);
	$all = file_get_contents("packs/$suff");unlink("packs/$suff");
	$arr = explode("\n",$all);
	$out='';
	foreach ($arr as $line)
	{
	    if (strlen($line)<3){ continue; }
	    $rec = explode(' :: ',$line);
	    if ($rec[0] == $id) { continue; }
	    $out.="$line\n";
	}
	if (strlen($out)>10)
	{    
	    file_put_contents("packs/$suff",$out);
	}
    }

}


// select file

$dir='packs';
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
	$files=array();
        while (($file = readdir($dh)) !== false) {
	    if (strlen($file)==3) { $files{$file}=filesize("$dir/$file"); }
        }
        closedir($dh);
    }
} else { echo "no such dir $dir\n"; }

arsort($files);
$middle = (count($files)/2);
if ($middle==0){$middle=count($files);}
$count=0;
$bigfiles=array();

foreach ($files as $fn=>$size)
{
    if (strlen($fn)==0){continue;}
    $count++;
    /*if ($count>$middle){break;}*/
    array_push($bigfiles,$fn);
}

shuffle($bigfiles);
$suff = $bigfiles[0];


// get pack

    if (file_exists("packs/$suff"))
    {
	$all = file_get_contents("packs/$suff");unlink("packs/$suff");
	$arr = explode("\n",$all);
	$modarr = array();
	foreach ($arr as $line)
	{
	    if (strlen($line)<3){ continue; }
	    $rec = explode(' :: ',$line);
	    $mod = $rec[1];
	    $modarr{$line}=$mod;
	}
	asort($modarr);
	$out='';$done=0;
	foreach ($modarr as $line=>$tmp)
	{
	    if (strlen($line)<3){ continue; }
	    $rec = explode(' :: ',$line);
	    $mod = $rec[1];
	    
	    //if ($done==0 && $now-$mod>120)
	    if ($done==0)
	    {
		$done=1;
		$id = $rec[0];
		$tim = time();
		$pack = $rec[2];
		$out .= "$id :: $tim :: $pack\n";
		echo "-=IDB=-$id-=IDE=-\n";
		echo "-=DMB=-$pack-=DBE=-\n";
	    }
	    else
	    {$out.="$line\n";}
	}
	if ($done==1)
	{
	    file_put_contents("packs/$suff",$out);
	}
    }




?>