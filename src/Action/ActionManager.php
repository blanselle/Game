<?php

namespace App\Action;

use App\Entity\Position;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ActionManager
{
    public function __construct(
        #[TaggedIterator('action')]
        private iterable $actions
    ) {
    }

    public function getActions(Position $from, Position $to)
    {
        $actions = [];
        /** @var ActionInterface $action */
        foreach ($this->actions as $action) {
            if ($action->support($from, $to)) {
                $actions []= $action;
            }
        }

        return $actions;
    }

    public function getAction(string $identifier): ?ActionInterface
    {
        /** @var ActionInterface $action */
        foreach ($this->actions as $action) {
            if ($identifier === $action->getIdentifier()) {
                return $action;
            }
        }

        return null;
    }
}
