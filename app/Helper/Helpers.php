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
                default:
                    $date = date('Y-m-d', $date);
            }
            return $date;
        }
    }

    if (!function_exists('date_to_timestamp')) {
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
                default:
                    $date = DateTime::createFromFormat("Y-m-d" , $date);
            }
            $date = $date->format('Y-m-d');
            $date = strtotime($date);
            return($date);
        }
    }
?>