<?php

$lat1 = $_GET['lat1'];
$lon1 = $_GET['lon1'];
$lat2 = -6.499865275377606;
$lon2 = 108.36054212092465;

$R = 6371; // radius bumi dalam kilometer

$dLat = deg2rad($lat2 - $lat1);
$dLon = deg2rad($lon2 - $lon1);

$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
$c = 2 * atan2(sqrt($a), sqrt(1-$a));

$distance = $R * $c;

echo "" . round($distance, 2) . " km";

?>