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

namespace Espo\Modules\Advanced\Core\Workflow\Formula\Functions\BpmGroup\CreatedEntityGroup;

use Espo\Core\Di\EntityManagerAware;
use Espo\Core\Di\EntityManagerSetter;
use Espo\Core\Di\InjectableFactoryAware;
use Espo\Core\Di\InjectableFactorySetter;
use Espo\Core\Exceptions\Error;
use Espo\Core\Formula\ArgumentList;
use Espo\Core\Formula\AttributeFetcher;
use Espo\Core\Formula\Functions\BaseFunction;

use RuntimeException;

class AttributeType extends BaseFunction implements EntityManagerAware, InjectableFactoryAware
{
    use EntityManagerSetter;
    use InjectableFactorySetter;

    public function process(ArgumentList $args)
    {
        $args = $this->evaluate($args);

        if (count($args) < 2) {
            throw new RuntimeException("Formula bpm\createdEntity\\attribute: Too few arguments.");
        }

        $aliasId = $args[0];
        $attribute = $args[1];

        if (!is_string($aliasId) || !is_string($attribute)) {
            throw new Error("Formula bpm\createdEntity\\attribute: Bad argument.");
        }

        $variables = $this->getVariables();

        if (!isset($variables->__createdEntitiesData)) {
            throw new Error("Formula bpm\createdEntity\\attribute: Can't be used out of BPM process.");
        }

        if (!isset($variables->__createdEntitiesData->$aliasId)) {
            throw new Error("Formula bpm\createdEntity\\attribute: Unknown aliasId.");
        }

        $entityType = $variables->__createdEntitiesData->$aliasId->entityType;
        $entityId = $variables->__createdEntitiesData->$aliasId->entityId;

        $entity = $this->entityManager->getEntityById($entityType, $entityId);

        $attributeFetched = $this->injectableFactory->create(AttributeFetcher::class);

        return $attributeFetched->fetch($entity, $attribute);
    }
}
