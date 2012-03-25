<?php

function is_in_polygon($coords, $lon, $lat) {
    $num = count($coords);
    $coords[$num] = $coords[0];
    
    $numleft = 0;
    $numright = 0;
    for ($i = 0; $i < $num; $i++) {
        $x1 = $coords[$i]["lat"];
        $x2 = $coords[$i + 1]["lat"];
        $y1 = $coords[$i]["lon"];
        $y2 = $coords[$i + 1]["lon"];
        //echo "($x1, $y1) ($x2,$y2)" . "<br />";
        if (max($x2, $x1) > $lon && min($x2, $x1) < $lon) {
            //echo "$x2, $x1, $lon". "<br />";
            if ($x2 > $x1) {
                $x3 = $x2;
                $x2 = $x1;
                $x1 = $x3;
                $y3 = $y2;
                $y2 = $y1;
                $y1 = $y3;
            }
            if (($x2 - $x1) * ($lat - $y1) - ($y2 - $y1) * ($lon - $x1) < 0) {
                $numleft++;
            } else {
                $numright++;
            }
        }
    }
    
    return !($numleft == 0 || $numright == 0) && abs($numleft - $numright) % 2 == 0;
}