<?php

namespace App\Action;

use App\Entity\Position;

interface ActionInterface
{
    public function getIdentifier(): string;

    public function getName(): string;

    public function support(Position $from, Position $to): bool;

    public function run(Position $from, Position $to): void;

    public function getReport(): string;
}
