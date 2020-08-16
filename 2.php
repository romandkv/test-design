<?php

function checkDateByFormat($date, $format) {
    return DateTime::createFromFormat($format, $date) ? true : false;
}

var_dump(checkDateByFormat(date('m:d:Y'), 'm:d:Y'));