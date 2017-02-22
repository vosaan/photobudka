<?php

function fOut($data, $die = false)
{
    if($die){
        echo "<pre>" . print_r($data, true) . "</pre>";
        die;
    } else {
        echo "<pre>" . print_r($data) . "</pre>";
    }
}