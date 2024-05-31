<?php

$position['App\Entity\Position'] = [];
for($i = -30; $i <= 30; $i++) {
    for($j = -30; $j <= 30; $j++) {
        $position['App\Entity\Position'][sprintf('position-%d-%d', $i, $j)] = [
            'x' => $i,
            'y' => $j,
            'ground' => '@ground_1'
        ];
    }
}

$position['App\Entity\Position'][sprintf('position-%d-%d', 0, 0)]['user'] = '@user_1';
$position['App\Entity\Position'][sprintf('position-%d-%d', 0, 2)]['user'] = '@user_2';

$position['App\Entity\Position'][sprintf('position-%d-%d', 0, 1)]['npc'] = '@npc_1';

$position['App\Entity\Position'][sprintf('position-%d-%d', 2, 3)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 3, 3)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 4, 3)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 1, 2)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 1, 1)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 2, 0)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 3, 0)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 4, 0)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 2, 2)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 3, 2)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 4, 2)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 2, 1)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 3, 1)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 4, 1)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 5, 2)]['ground'] = '@ground_2';
$position['App\Entity\Position'][sprintf('position-%d-%d', 5, 1)]['ground'] = '@ground_2';


$position['App\Entity\Position'][sprintf('position-%d-%d', -1, 5)]['ground'] = '@ground_3';
$position['App\Entity\Position'][sprintf('position-%d-%d', -1, 4)]['ground'] = '@ground_3';
$position['App\Entity\Position'][sprintf('position-%d-%d', -1, 3)]['ground'] = '@ground_3';
$position['App\Entity\Position'][sprintf('position-%d-%d', -1, 2)]['ground'] = '@ground_3';
$position['App\Entity\Position'][sprintf('position-%d-%d', -1, 1)]['ground'] = '@ground_3';
$position['App\Entity\Position'][sprintf('position-%d-%d', -1, 0)]['ground'] = '@ground_3';
$position['App\Entity\Position'][sprintf('position-%d-%d', -1, -1)]['ground'] = '@ground_3';
$position['App\Entity\Position'][sprintf('position-%d-%d', -1, -2)]['ground'] = '@ground_3';
$position['App\Entity\Position'][sprintf('position-%d-%d', -1, -3)]['ground'] = '@ground_3';
$position['App\Entity\Position'][sprintf('position-%d-%d', -1, -4)]['ground'] = '@ground_3';
$position['App\Entity\Position'][sprintf('position-%d-%d', -1, -5)]['ground'] = '@ground_3';

return $position;
