<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Enum\Equipment\WeaponPosition;
use App\Enum\Equipment\WeaponType;
use App\Manager\EquipmentManager;
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
    #[Route('/equipment/weapons', name: 'equipment_weapons')]
    public function equipment(
        Request $request,
        EquipmentRepository $equipmentRepository,
        EquipmentManager $equipmentManager
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
                'label' => false,
                'required' => false
            ])
            ->add('equip', SubmitType::class, ['label' => 'Equiper'])
            ->add('equip_left_hand', SubmitType::class, ['label' => 'Equiper main gauche'])
            ->add('equip_right_hand', SubmitType::class, ['label' => 'Equiper main droite'])
            ->add('equip_drop_weapons', SubmitType::class, ['label' => 'Tout lâcher'])
        ->getForm();

        $unwornWeaponsForm->handleRequest($request);

        if ($unwornWeaponsForm->isSubmitted() && $unwornWeaponsForm->isValid()) {
            if ($unwornWeaponsForm->get('equip_drop_weapons')->isClicked()) {
                $equipmentManager->dropWeapons($this->getUser());

                return $this->redirectToRoute('equipment_weapons');
            }

            $data = $unwornWeaponsForm->getData();
            /** @var Equipment $weapon */
            $weapon = array_shift($data);

            $positionToEquip = null;
            if ($unwornWeaponsForm->get('equip_left_hand')->isClicked()) {
                $positionToEquip = WeaponPosition::leftHand->value;
            }
            if ($unwornWeaponsForm->get('equip_right_hand')->isClicked()) {
                $positionToEquip = WeaponPosition::rightHand->value;
            }

            $equipmentManager->equip($this->getUser(), $weapon, $positionToEquip);

            return $this->redirectToRoute('equipment_weapons');
        }

        return new Response($this->renderView('equipment/weapons.html.twig', [
            'wornWeapons' => $wornWeapons,
            'unwornWeaponsForm' => $unwornWeaponsForm
        ]));
    }
}
