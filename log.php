<?php

require('dblog.php');


if ($_FILES["fileToUpload"]["error"] > 0){
    echo "Apologies, an error has occurred.";
    echo "Error Code: " . $_FILES["fileToUpload"]["error"];
}
else{
	if ($_FILES["fileToUpload"]["size"] < 10000){
		if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])){
			$path_parts = pathinfo($_FILES["fileToUpload"]["name"]);
			//die (print_r($path_parts));
			$filename = $path_parts['filename'];
			$filePointer = fopen($_FILES['fileToUpload']['tmp_name'], "rb");		
			$param = array();
			$param['Filename'] = $path_parts['basename'];
			$was_blank = FALSE;
			while ($filePointer && !feof($filePointer)){
				$rawdata = fgets($filePointer,2048);
				$temprawdata = trim($rawdata);
				$was_blank = (bool) ($was_blank) || (bool) (empty($temprawdata));
				if (!$was_blank){
					$n = strpos($rawdata,':');
					if ($n>0){
						$data[0] = substr($rawdata,0,$n);
						$data[1] = ltrim(substr($rawdata,$n+1));
						$param[trim($data[0])] = trim($data[1]);
						unset($data);
					}
				}
				elseif (!empty($temprawdata))						
					//$param['Message'] .= trim($rawdata) . (empty($param['Message']) ? "" :  "\015\012") ;
					$param['Message'] .= $rawdata;
			}			
			unset ($was_blank);			
			fclose($filePointer);	
			//print_r($param);		
			$retval = dblog($param);		
		}
    }
	else{
		echo "Files must be less than 10,000 kb"; 
		echo "<table border=\"1\">";  
		echo "<tr><td>Client Filename: </td><td>" . $_FILES["fileToUpload"]["name"] . "</td></tr>";
		echo "<tr><td>File Type: </td><td>" . $_FILES["fileToUpload"]["type"] . "</td></tr>";
		echo "<tr><td>File Size: </td><td>" . ($_FILES["fileToUpload"]["size"] / 1024) . " Kb</td></tr>";
		echo "<tr><td>Name of Temporary File: </td><td>" . $_FILES["fileToUpload"]["tmp_name"] . "</td></tr>";
		echo "</table>";  	  
	}
}
echo "$retval";
?>