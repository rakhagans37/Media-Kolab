<?php
require_once __DIR__ . "/getConnectionMsqli.php";
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/cloudinary.php";
require_once __DIR__ . "/hash.php";

function isAlphanumeric($str)
{
    return preg_match('/[a-zA-Z]/', $str) && preg_match('/\d/', $str);
}

function getYoutubeID($youtubeUrl)
{
    $urlParts = parse_url($youtubeUrl);
    parse_str($urlParts['query'], $query);

    // Get the video ID
    $videoId = $query['v'];

    return $videoId;
}
