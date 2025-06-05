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

function convertToWebP(string $sourcePath, string $destinationPath, int $quality = 80): bool {
    $info = getimagesize($sourcePath);

    if (!$info) {
        return false;
    }

    switch ($info['mime']) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($sourcePath);
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
            break;
        default:
            return false;
    }

    $success = imagewebp($image, $destinationPath, $quality);
    imagedestroy($image);

    return $success;
}


