<?php 
	if (!function_exists('format_date')) {
		function format_date($date, $date_format)
		{
			switch ($date_format) {
				case "yyyy/mm/dd":
					$date = date('Y/m/d', $date);
					break;
				case "yyyy.mm.dd":
					$date = date('Y.m.d', $date);
					break;
				case "yyyy-mm-dd":
					$date = date('Y-m-d', $date);
					break;
				case "dd/mm/yyyy":
					$date = date('d/m/Y', $date);
					break;
				case "dd-mm-yyyy":
					$date = date('d-m-Y', $date);
					break;
				case "dd.mm.yyyy":
					$date = date('d.m.Y', $date);
					break;
			}

			// if ($date_format === "yyyy/mm/dd") {
	  //   		$date = date('Y/m/d', $date);
	  //   	}
	  //   	else if ($date_format === "yyyy.mm.dd") {
	  //   		$date = date('Y.m.d', $date);
	  //   	}
	  //   	else if ($date_format === "yyyy-mm-dd") {
	  //   		$date = date('Y-m-d', $date);
	  //   	}
	  //   	else if ($date_format === "dd/mm/yyyy") {
	  //   		$date = date('d/m/Y', $date);
	  //   	}
	  //   	else if ($date_format === "dd-mm-yyyy") {
	  //   		$date = date('d-m-Y', $date);
	  //   	}
	  //   	else if ($date_format === "dd.mm.yyyy") {
	  //   		$date = date('d.m.Y', $date);
	  //   	}
	    	return $date;
		}
	}

	function date_to_timestamp($date_format, $date)
    {
        switch ($date_format) {
            case "yyyy/mm/dd":
                $date = DateTime::createFromFormat("Y/m/d" , $date);
                break;
            case "yyyy.mm.dd":
                $date = DateTime::createFromFormat("Y.m.d" , $date);
                break;
            case "yyyy-mm-dd":
                $date = DateTime::createFromFormat("Y-m-d" , $date);
                break;
            case "dd/mm/yyyy":
                $date = DateTime::createFromFormat("d/m/Y" , $date);
                break;
            case "dd-mm-yyyy":
                $date = DateTime::createFromFormat("d-m-Y" , $date);
                break;
            case "dd.mm.yyyy":
                $date = DateTime::createFromFormat("d.m.Y" , $date);
                break;
        }
        $date = $date->format('Y-m-d');
		$date = strtotime($date);
        return($date);
    }
?>