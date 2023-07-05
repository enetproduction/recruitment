<?php

declare(strict_types=1);

namespace Doctrine\ORM;


class EntityManager
{
    public function getReference($className, $shipmentId): object
    {
        return new \stdClass();
    }

    public function persist($entity)
    {
    }

    public function flush()
    {

    }
}
