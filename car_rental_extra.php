<?php

function getOptimizedCost($seat, $cars) {
    // Sort cars by cost per seat in ascending order
    uasort($cars, function($a, $b) {
        return ($a['cost'] / $a['capacity']) <=> ($b['cost'] / $b['capacity']);
    });

    $result = [];
    $totalCost = 0;

    // Calculate the optimized cost
    foreach ($cars as $size => $car) {
        while ($seat >= $car['capacity']) {
            $numCars = intdiv($seat, $car['capacity']);
            $result[$size] = $numCars;
            $totalCost += $numCars * $car['cost'];
            $seat -= $numCars * $car['capacity'];
        }
    }

    if ($seat > 0) {
        foreach ($cars as $size => $car) {
            if ($car['capacity'] >= $seat) {
                $result[$size] = isset($result[$size]) ? $result[$size] + 1 : 1;
                $totalCost += $car['cost'];
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

// Define car rental costs and capacities (generic)
$cars = [
    'S' => ['capacity' => 5, 'cost' => 5000],
    'M' => ['capacity' => 9, 'cost' => 8000],
    'L' => ['capacity' => 15, 'cost' => 11000]
];

list($result, $totalCost) = getOptimizedCost($seat, $cars);

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
