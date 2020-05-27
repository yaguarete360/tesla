<?php
function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
{
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);
    $dateArr = array();
    do
    {
        if(date("w", $startDate) != $weekdayNumber)
        {
            $startDate += (24 * 3600);
        }
    } 
    while(date("w", $startDate) != $weekdayNumber);
    while($startDate <= $endDate)
    {
        $dateArr[] = date('Y-m-d', $startDate);
        $startDate += (7 * 24 * 3600);
    }
    return($dateArr);
}
