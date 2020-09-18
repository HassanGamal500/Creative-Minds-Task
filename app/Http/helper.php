<?php
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


function setActive($path)
{
    return Request::is($path . '*') ? ' active' :  '';
}

function convert($string) {
    $arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩',','];
    $num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
    $englishNumbersOnly = str_replace($arabic, $num, $string);

    return $englishNumbersOnly;
}