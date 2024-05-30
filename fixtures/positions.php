<?php

$position['App\Entity\Position'] = [];
for($i = -30; $i <= 30; $i++) {
    for($j = -20; $j <= 20; $j++) {
        $position['App\Entity\Position'][sprintf('position-%d-%d', $i, $j)] = [
            'x' => $i,
            'y' => $j,
            'ground' => '@ground_1'
        ];
    }
}

$position['App\Entity\Position'][sprintf('position-%d-%d', 0, 0)]['user'] = '@user_1';

return $position;
