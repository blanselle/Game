<?php

namespace App\Controller;

use App\Action\ActionManager;
use App\Entity\Position;
use App\Entity\User;
use App\Manager\FighterManager;
use App\Repository\EventRepository;
use App\Repository\PositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game/view', name: 'game_view')]
    public function view(
        Request $request,
        PositionRepository $positionRepository,
        ActionManager $actionManager,
        EventRepository $eventRepository
    ): Response {

        /** @var Position $position */
        $position = $positionRepository->findOneByid($request->get('positionId', null));
        if (null === $position) {
            $position = $this->getUser()->getPosition();
        }

        $positions = $positionRepository->getMapArround($this->getUser()->getPosition());

        $actions = $actionManager->getActions($this->getUser()->getPosition(), $position);

        $actionIdentifier = $request->get('action', null);
        if (null !== $actionIdentifier) {
            $action = $actionManager->getAction($actionIdentifier);
            if (null !== $action) {
                $action->run($this->getUser()->getPosition(), $position);
                if ($action->getReport() != '') {
                    $this->addFlash('hasReport', 'true');
                    $this->addFlash('report', $action->getReport());
                }

                return $this->redirectToRoute('game_view');
            }
        }

        return new Response($this->renderView('game/view.html.twig', [
            'positions' => $positions,
            'targetPosition' => $position,
            'actions' => $actions,
            'targetEvents' => $position->getFighter() ? $eventRepository->getVisibleEvents($position->getFighter(), $this->getUser()) : []
        ]));
    }

    #[Route('/game/init', name: 'game_init')]
    public function init(FighterManager $fighterManager): Response
    {
        $messages = [];
        /** @var User $user */
        $user = $this->getUser();
        if (0 >= $user->getHealth() || null === $user->getPosition()) {
            $user = $fighterManager->resurrect($user);
            $messages []= sprintf(
                'Vous avez été tué. Vous réaparaitrez en x:%d | y:%d ',
                $user->getPosition()->getX(),
                $user->getPosition()->getY()
            );
        }

        return new Response($this->renderView('game/init.html.twig', [
            'messages'=> $messages
        ]));
    }
}
