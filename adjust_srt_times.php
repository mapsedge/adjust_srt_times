<?php

// Define the path to your subtitle file
$filename = 'subtitle_filename.srt';
$delay = 4; // Delay in seconds

// Function to add seconds to a time string
function addSecondsToTime($time, $secondsToAdd) {
    list($hours, $minutes, $secondsAndMillis) = explode(':', $time);
    list($seconds, $milliseconds) = explode(',', $secondsAndMillis);
    
    $totalMilliseconds = ($hours * 3600 + $minutes * 60 + $seconds) * 1000 + $milliseconds;
    $totalMilliseconds += $secondsToAdd * 1000;
    
    $hours = floor($totalMilliseconds / 3600000);
    $minutes = floor(($totalMilliseconds % 3600000) / 60000);
    $seconds = floor(($totalMilliseconds % 60000) / 1000);
    $milliseconds = $totalMilliseconds % 1000;
    
    return sprintf("%02d:%02d:%02d,%03d", $hours, $minutes, $seconds, $milliseconds);
}

// Read the file content
$content = file_get_contents($filename);

if ($content === false) {
    die("Error reading the file.");
}

// Pattern to match the subtitle time code lines
$pattern = '/(\d{2}:\d{2}:\d{2},\d{3}) --> (\d{2}:\d{2}:\d{2},\d{3})/';


// Function to adjust the time codes
$adjustedContent = preg_replace_callback($pattern, function($matches) use ($delay) {
    $start = addSecondsToTime($matches[1], $delay);
    $end = addSecondsToTime($matches[2], $delay);
    return "$start --> $end";
}, $content);

// Write the adjusted content back to the file
$result = file_put_contents($filename . "_", $adjustedContent);

if ($result === false) {
    die("Error writing the file.");
}

echo "Subtitle times have been adjusted successfully." . PHP_EOL . PHP_EOL;

