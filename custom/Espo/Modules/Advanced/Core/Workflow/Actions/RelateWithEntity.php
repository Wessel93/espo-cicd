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

namespace Espo\Modules\Advanced\Core\Workflow\Actions;

use Espo\Core\Exceptions\Error;
use Espo\ORM\Entity;
use stdClass;

class RelateWithEntity extends BaseEntity
{
    protected function run(Entity $entity, stdClass $actionData): bool
    {
        if (empty($actionData->entityId) || empty($actionData->link)) {
            throw new Error('Workflow['.$this->getWorkflowId().']: Bad params defined for RelateWithEntity');
        }

        $foreignEntityType = $entity->getRelationParam($actionData->link, 'entity');

        if (!$foreignEntityType) {
            throw new Error('Workflow['.$this->getWorkflowId().
                ']: Could not find foreign entity type for RelateWithEntity');
        }

        $foreignEntity = $this->getEntityManager()->getEntityById($foreignEntityType, $actionData->entityId);

        if (!$foreignEntity) {
            throw new Error('Workflow['.$this->getWorkflowId().
                ']: Could not find foreign entity for RelateWithEntity');
        }

        $this->getEntityManager()
            ->getRDBRepository($entity->getEntityType())
            ->getRelation($entity, $actionData->link)
            ->relate($foreignEntity);

        return true;
    }
}