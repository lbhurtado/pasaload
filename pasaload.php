<?php
	//include('../Glimpse/index.php');
	require_once("conf.php");      
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>PasaLoad</title>
	</head>
	<body> 
		<?php
			$dg = new C_DataGrid("select * from `demo`", "id", "demo");	
			$dg -> set_col_title('id', 			'ID'		 ) -> set_col_property('id',       array('width'     => 15,
																							   		 'align'     => 'center',
																							   		 'formatter' => '###idFormatter###'));
			$dg -> set_col_title('datetime', 	'Date/Time'	 ) -> set_col_property('datetime', array('width'     => 35,
																							   		 'align'     => 'center'));
			$dg -> set_col_title('amount', 		'Amount'	 ) -> set_col_property('amount',   array('width'     => 15,
																							   		 'align'     => 'right',
																							   		 'formatter' => '###currencyFormatter###'));
			$dg -> set_col_title('origin', 		'Origin'	 ) -> set_col_property('origin',   array('width'     => 20,
																							   		 'align'     => 'center'));
			$dg -> set_col_title('destination', 'Destination') -> set_col_hidden("destination");

			$dg -> set_caption	('PasaLoad List');
			$dg -> enable_export('EXCEL');
			$dg -> enable_search(TRUE) -> enable_advanced_search(TRUE);
			$dg -> set_dimension(400,85);
			//$dg -> set_pagesize	(40);
			$dg -> set_scroll	(TRUE);
			//$dg -> set_theme	('dot-luv');
			//$dg -> enable_autowidth(TRUE);
			$dg -> display();
		?>
		<script>
			function pad(num, size) {
			    var s = num+"";
			    while (s.length < size) s = "0" + s;
			    return s;
			}
		    function idFormatter (cellValue, options, rowdata) {
		        if (cellValue==0){
		            return 'N/A';
		        }
		        return pad(cellValue, 5);
		    }
			function currencyFormatter (cellValue, options, rowdata) {
				var DecimalSeparator = Number("1.2").toLocaleString().substr(1,1);
				var AmountWithCommas = cellValue.toLocaleString();
				var arParts = String(AmountWithCommas).split(DecimalSeparator);
				var intPart = arParts[0];
				var decPart = (arParts.length > 1 ? arParts[1] : '');
				decPart = (decPart + '00').substr(0,2);
				return 'P ' + intPart + DecimalSeparator + decPart;
			}
		</script>
	</body>
</html>
