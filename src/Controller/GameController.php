<?php

namespace App\Controller;

use App\Repository\PositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class GameController extends AbstractController
{
    #[Route('/game/view', name: 'game_view')]
    public function view(Request $request, PositionRepository $positionRepository): Response
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

        return new Response($this->renderView('game/view.html.twig', [
            'positions' => $positions,
            'position' => $position
        ]));
    }
}
