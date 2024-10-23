<?php

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
