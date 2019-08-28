<?php 
	if (!function_exists('format_date')) {
		function format_date($date, $date_format)
		{
			if ($date_format === "yyyy/mm/dd") {
	    		$date = date('Y/m/d', $date);
	    	}
	    	else if ($date_format === "yyyy.mm.dd") {
	    		$date = date('Y.m.d', $date);
	    	}
	    	else if ($date_format === "yyyy-mm-dd") {
	    		$date = date('Y-m-d', $date);
	    	}
	    	else if ($date_format === "dd/mm/yyyy") {
	    		$date = date('d/m/Y', $date);
	    	}
	    	else if ($date_format === "dd-mm-yyyy") {
	    		$date = date('d-m-Y', $date);
	    	}
	    	else if ($date_format === "dd.mm.yyyy") {
	    		$date = date('d.m.Y', $date);
	    	}
	    	return $date;
		}
	}
?>