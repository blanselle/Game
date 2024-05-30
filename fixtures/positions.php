<?php

$position['App\Entity\Position'] = [];
for($i = -30; $i <= 30; $i++) {
    for($j = -20; $j <= 20; $j++) {
        $position['App\Entity\Position'][sprintf('position-%s-%s', $i, $j)] = [
            'x' => $i,
            'y' => $j,
        ];
    }
}

return $position;
