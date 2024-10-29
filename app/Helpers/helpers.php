<?php

use Carbon\Carbon;


if (!function_exists('generateUniqueId')) {
    function generateUniqueId($dept_id)
    {
        $datePart = date('Ymd');
        $uniqueNumber = substr(uniqid(), -5);
        $dept_id = str_pad($dept_id, 2, '0', STR_PAD_LEFT);
        $uniqueId = $datePart . $uniqueNumber . $dept_id;

        return $uniqueId;
    }
}

if (!function_exists('formatTanggal')) {
    function formatTanggalWithDayAndTime($date)
    {
            return Carbon::parse($date)
                ->locale('id') 
                ->isoFormat('dddd, DD-MM-YY HH:mm');
    }
    
}
