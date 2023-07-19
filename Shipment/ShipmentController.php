<?php

namespace App\Shipment;

use Doctrine\ORM\EntityManager;

class ShipmentController
{
    private EntityManager $em;

    private array $dpdSpecialParams;

    public function __construct(
        EntityManager $em,
        array $dpdSpecialParams
    ) {
        $this->em = $em;
        $this->dpdSpecialParams = $dpdSpecialParams;
    }

    public function process(int $shipmentId): bool
    {
        if ($shipmentId !== 0) {
            /** @var ShipmentEntity $entity */
            $entity = $this->em->getReference('ShipmentEntity', $shipmentId);
            $result = null;
            if ($entity != null) {
                switch ($entity->transportType) {
                    case 'inpost':
                        $result = $this->generateInpost($entity);
                        break;
                    case 'dpd':
                        $result = $this->generateDpd($entity, $this->dpdSpecialParams);
                        break;
                    case 'personal_collection':
                    default:
                }

                if ($result != null) {
                    $deliveryTime = isset($result['expected_delivery_time']) ? $result['expected_delivery_time'] : null
                    $waybill = new WaybillEntity($entity->id, $result['waybill_no'], ['delivery_time' => $deliveryTime]);
                    $this->em->persist($waybill);
                    $this->em->flush();

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    private function generateInpost($shipment): array
    {
        // Imagine that there is some integration logic for Inpost.
        return ['waybill_no' => '12345'];
    }

    private function generateDpd($shipment, $options): array
    {
        // Imagine that there is some integration logic for DPD with some extra params that DPD needed.
        return ['waybill_no' => '12345', 'expected_delivery_time' => '2023-01-01'];
    }
}
