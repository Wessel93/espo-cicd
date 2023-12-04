<?php
/*********************************************************************************
 * The contents of this file are subject to the EspoCRM Advanced Pack
 * Agreement ("License") which can be viewed at
 * https://www.espocrm.com/advanced-pack-agreement.
 * By installing or using this file, You have unconditionally agreed to the
 * terms and conditions of the License, and You may not use this file except in
 * compliance with the License.  Under the terms of the license, You shall not,
 * sublicense, resell, rent, lease, distribute, or otherwise  transfer rights
 * or usage to the software.
 *
 * Copyright (C) 2015-2023 Letrium Ltd.
 *
 * License ID: 8574d8ccb6560d675a7f3219930bc620
 ***********************************************************************************/

namespace Espo\Modules\Advanced\Core\ORM;

use Espo\Core\InjectableFactory;
use Espo\Core\ORM\Entity;
use Espo\ORM\EntityManager;

/**
 * Creates entities with custom defs. Need for supporting foreign fields like `linkName.attribute`.
 */
class CustomEntityFactory
{
    /** @var InjectableFactory */
    private $injectableFactory;
    /** @var EntityManager */
    private $entityManager;

    public function __construct(
        InjectableFactory $injectableFactory,
        EntityManager $entityManager
    ) {
        $this->injectableFactory = $injectableFactory;
        $this->entityManager = $entityManager;
    }

    public function create(string $entityType, array $fieldDefs): Entity
    {
        return $this->createImplementation($entityType, $fieldDefs);
    }

    private function createImplementation(string $entityType, array $fieldDefs): Entity
    {
        $seed = $this->entityManager->getEntityFactory()->create($entityType);

        $className = get_class($seed);

        $defs = $this->entityManager->getMetadata()->get($entityType);

        if (array_key_exists('attributes', $defs)) {
            $defs['attributes'] = array_merge($defs['attributes'], $fieldDefs);
        }
        else {
            $defs['fields'] = array_merge($defs['fields'], $fieldDefs);
        }

        return $this->injectableFactory->createWith($className, [
            'entityType' => $entityType,
            'defs' => $defs,
            'valueAccessorFactory' => null,
        ]);
    }
}
