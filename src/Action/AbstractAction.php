<?php

namespace App\Action;

use App\Entity\Position;
use App\Manager\FighterManager;
use Twig\Environment;

class AbstractAction implements ActionInterface
{
    public function __construct(
        protected FighterManager $fighterManager,
        protected Environment $twig,
        protected string $report = '',
    )
    {}

    public function getIdentifier(): string
    {
        return 'abstract_action';
    }

    public function getName(): string
    {
        return 'Abstract Action';
    }

    protected function getDistance(Position $from, Position $to): int
    {
        return (int)(sqrt(
            pow($to->getX()-$from->getX(), 2) + pow($to->getY()-$from->getY(), 2))
        );
    }

    public function support(Position $from, Position $to): bool
    {
        return false;
    }

    public function run(Position $from, Position $to): void
    {
    }

    public function getReport(): string
    {
        return $this->report;
    }
}
