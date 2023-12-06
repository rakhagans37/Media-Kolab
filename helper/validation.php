<?php
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
