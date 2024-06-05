<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Enum\Equipment\WeaponType;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipmentController extends AbstractController
{
    #[Route('/equipment', name: 'equipment')]
    public function equipment(
        Request $request,
        EquipmentRepository $equipmentRepository,
    ): Response {
        $wornWeapons = $equipmentRepository->getOrderedWornEquipment($this->getUser(), WeaponType::values(), true);

        $unwornWeaponsForm = $this->createFormBuilder()
            ->add('wornWeampons', EntityType::class, [
                'class' => Equipment::class,
                'query_builder' => function (EquipmentRepository $er): QueryBuilder {
                    return $er->getOrderedWornEquipmentQueryBuilder($this->getUser(), WeaponType::values(), false);
                },
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'label' => false
            ])
            ->add('Equiper', SubmitType::class)
        ->getForm();

        $unwornWeaponsForm->handleRequest($request);

        if ($unwornWeaponsForm->isSubmitted() && $unwornWeaponsForm->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $unwornWeaponsForm->getData();
            dd($data);
            // user courant peut quiper cette arme, alors on désequipe l'arme et on équipe la nouvelle.
            // Si on ne peut pas deviner l'emplacement, on le demande
        }

        return new Response($this->renderView('equipment/equipment.html.twig', [
            'wornWeapons' => $wornWeapons,
            'unwornWeaponsForm' => $unwornWeaponsForm
        ]));
    }
}
