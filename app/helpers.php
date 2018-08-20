<?php
// My common functions
function isEmployee() {
    $user = Auth::user();
    $roleArr = explode('|', $user->role);
    return in_array('0', $roleArr);
}

function isEmployer() {
    $user = Auth::user();
    $roleArr = explode('|', $user->role);
    return in_array('1', $roleArr);
}

function firstDateOfCurrentWeek() {
    return Date('d-m-Y', strtotime('this week', time()));
}

function lastDateOfCurrentWeek() {
    return Date('d-m-Y', strtotime('this week +4 days', time()));
}

function firstDateOfTheWeek($weekNumber, $year) {
    $firstMondayOfTheYear = strtotime("mon jan {$year}");
    $weeksOffset = $weekNumber - date('W', $firstMondayOfTheYear);
    $mondayOfTheWeek = strtotime("+{$weeksOffset} week " . date('Y-m-d', $firstMondayOfTheYear));
    return Date('d-m-Y', $mondayOfTheWeek);
}

function lastDateOfTheWeek($weekNumber, $year) {
    $firstSundayOfTheYear = strtotime("fri jan {$year}");
    $weeksOffset = $weekNumber - date('W', $firstSundayOfTheYear);
    $sundayOfTheWeek = strtotime("+{$weeksOffset} week " . date('Y-m-d', $firstSundayOfTheYear));
    return Date('d-m-Y', $sundayOfTheWeek);
}