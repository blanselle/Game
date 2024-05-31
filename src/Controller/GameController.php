<?php

namespace App\Controller;

use App\Action\ActionManager;
use App\Repository\PositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class GameController extends AbstractController
{
    #[Route('/game/view', name: 'game_view')]
    public function view(Request $request, PositionRepository $positionRepository, ActionManager $actionManager): Response
    {
        $position = $positionRepository->findOneByid($request->get('positionId', null));
        if (null === $position) {
            $position = $this->getUser()->getPosition();
        }

        $positions = $positionRepository->getMapArround(
            $this->getUser()->getPosition()->getX(),
            $this->getUser()->getPosition()->getY(),
            5
        );

        $actions = $actionManager->getActions($this->getUser()->getPosition(), $position);

        $actionIdentifier = $request->get('action', null);
        if (null !== $actionIdentifier) {
            $action = $actionManager->getAction($actionIdentifier);
            if (null !== $action) {
                $action->run($this->getUser()->getPosition(), $position);

                return $this->redirectToRoute('game_view');
            }
        }

        return new Response($this->renderView('game/view.html.twig', [
            'positions' => $positions,
            'position' => $position,
            'actions' => $actions
        ]));
    }
}
