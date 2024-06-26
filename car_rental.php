<?php

function getOptimizedCost($seat) {
    // Define car rental costs and capacities
    $cars = [
        'S' => ['capacity' => 5, 'cost' => 5000],
        'M' => ['capacity' => 10, 'cost' => 8000],
        'L' => ['capacity' => 15, 'cost' => 12000]
    ];
    
    // Sort cars by cost per seat in ascending order
    uasort($cars, function($a, $b) {
        return ($a['cost'] / $a['capacity']) <=> ($b['cost'] / $b['capacity']);
    });

    $result = [];
    $totalCost = 0;
    $foundSolution = false;

    // Calculate the optimized cost
    foreach ($cars as $size => $car) {
        while ($seat >= $car['capacity']) {
            $numCars = intdiv($seat, $car['capacity']);
            $result[$size] = $numCars;
            $totalCost += $numCars * $car['cost'];
            $seat -= $numCars * $car['capacity'];
        }
        
        if (!$foundSolution && count($result) > 0) {
            $foundSolution = true;
            break;
        }
    }

    
    if ($seat > 0 && !$foundSolution) {
        foreach ($cars as $size => $car) {
            if ($car['capacity'] >= $seat) {
                $result[$size] = isset($result[$size]) ? $result[$size] + 1 : 1;
                $totalCost += $car['cost'];
                $foundSolution = true;
                break;
            }
        }
    }

    return [$result, $totalCost];
}

echo "Please input number (seat): ";
$handle = fopen("php://stdin", "r");
$seat = intval(fgets($handle));
fclose($handle);

list($result, $totalCost) = getOptimizedCost($seat);

// Print the result (only the first solution found)
$firstResult = true;
foreach ($result as $size => $count) {
    if (!$firstResult) {
        echo " or\n";
    }
    echo "$size x $count";
    $firstResult = false;
}
echo "\nTotal = PHP $totalCost\n";
?>
