<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Enum\Equipment\ArmorPosition;
use App\Enum\Equipment\ArmorType;
use App\Enum\Equipment\WeaponPosition;
use App\Enum\Equipment\WeaponType;
use App\Manager\EquipmentManager;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipmentController extends AbstractController
{
    #[Route('/equipment/weapons', name: 'equipment_weapons')]
    public function equipmentWeapons(
        Request $request,
        EquipmentRepository $equipmentRepository,
        EquipmentManager $equipmentManager
    ): Response {
        $wornWeapons = $equipmentRepository->getOrderedEquipment($this->getUser(), WeaponType::values(), true);

        $unwornWeaponsForm = $this->createFormBuilder()
            ->add('unwornWeampon', EntityType::class, [
                'class' => Equipment::class,
                'query_builder' => function (EquipmentRepository $er): QueryBuilder {
                    return $er->getOrderedEquipmentQueryBuilder($this->getUser(), WeaponType::values(), false);
                },
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'label' => false,
                'required' => false
            ])
            ->add('weaponPosition', ChoiceType::class, [
                'choices' => [
                    'Main droite' => WeaponPosition::rightHand->value,
                    'Main gauche' => WeaponPosition::leftHand->value,
                    'Deux main' => WeaponPosition::twoHands->value,
                ]
            ])
            ->add('equip', SubmitType::class, ['label' => 'Equiper'])
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
            $weapon = $data['unwornWeampon'];
            $positionToEquip = $data['weaponPosition'];

            if (!in_array($positionToEquip, $weapon->getAvailablePositions())) {
                return $this->redirectToRoute('equipment_weapons');
            }

            $equipmentManager->equip($this->getUser(), $weapon, $positionToEquip);

            return $this->redirectToRoute('equipment_weapons');
        }

        return new Response($this->renderView('equipment/weapons.html.twig', [
            'wornWeapons' => $wornWeapons,
            'unwornWeaponsForm' => $unwornWeaponsForm
        ]));
    }

    #[Route('/equipment/armors', name: 'equipment_armors')]
    public function equipmentArmors(
        Request $request,
        EquipmentRepository $equipmentRepository,
        EquipmentManager $equipmentManager
    ): Response {
        $wornArmors = $equipmentRepository->getOrderedEquipment($this->getUser(), ArmorType::values(), true);

        $unwornArmorsForm = $this->createFormBuilder()
            ->add('unwornArmors', EntityType::class, [
                'class' => Equipment::class,
                'query_builder' => function (EquipmentRepository $er): QueryBuilder {
                    return $er->getOrderedEquipmentQueryBuilder($this->getUser(), ArmorType::values(), false);
                },
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'label' => false,
                'required' => false
            ])
            ->add('armorPosition', ChoiceType::class, [
                'choices' => [
                    'Tête' => ArmorPosition::head->value,
                    'Cou' => ArmorPosition::neck->value,
                    'Poitrine' => ArmorPosition::chest->value,
                    'Anneau main droite' => ArmorPosition::rightRing->value,
                    'Anneau main gauche' => ArmorPosition::leftWrist->value,
                    'Poignet gauche' => ArmorPosition::leftRing->value,
                    'Poignet droit' => ArmorPosition::rightRing->value,
                    'Jambes' => ArmorPosition::legs->value,
                    'pieds' => ArmorPosition::feet->value,
                ]
            ])
            ->add('equip', SubmitType::class, ['label' => 'Equiper'])
            ->add('equip_drop_armors', SubmitType::class, ['label' => 'Tout enlever'])
            ->getForm();

        $unwornArmorsForm->handleRequest($request);

        if ($unwornArmorsForm->isSubmitted() && $unwornArmorsForm->isValid()) {
            if ($unwornArmorsForm->get('equip_drop_armors')->isClicked()) {
                $equipmentManager->dropArmors($this->getUser());

                return $this->redirectToRoute('equipment_armors');
            }

            $data = $unwornArmorsForm->getData();
            /** @var Equipment $armor */
            $armor = $data['unwornArmors'];
            $positionToEquip = $data['armorPosition'];

            if (!in_array($positionToEquip, $armor->getAvailablePositions())) {
                return $this->redirectToRoute('equipment_armors');
            }

            $equipmentManager->equip($this->getUser(), $armor, $positionToEquip);

            return $this->redirectToRoute('equipment_armors');
        }

        return new Response($this->renderView('equipment/armors.html.twig', [
            'wornArmors' => $wornArmors,
            'unwornArmorsForm' => $unwornArmorsForm
        ]));
    }
}
