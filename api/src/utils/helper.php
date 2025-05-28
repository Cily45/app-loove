<?php
function cleanString(string $value): string
{
    return trim(htmlspecialchars($value, ENT_QUOTES));
}

function toDate(string $value): string
{
    $array = explode("/", $value);

    if (count($array) !== 3) {
        throw new Exception("Format de date invalide : $value");
    }

    return $array[2] . '-' . $array[1] . '-' . $array[0];
}


