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

namespace Espo\Modules\Advanced\Core\Workflow\Formula\Functions\WorkflowGroup;

use Espo\Core\Formula\ArgumentList;
use Espo\Core\Formula\Functions\BaseFunction;
use Espo\Modules\Advanced\Tools\Workflow\Service;
use Espo\Core\Exceptions\Error;
use Espo\Core\Di\InjectableFactoryAware;
use Espo\Core\Di\InjectableFactorySetter;
use Espo\Core\Di\EntityManagerAware;
use Espo\Core\Di\EntityManagerSetter;

class TriggerType extends BaseFunction implements

    InjectableFactoryAware,
    EntityManagerAware
{
    use InjectableFactorySetter;
    use EntityManagerSetter;

    public function process(ArgumentList $args)
    {
        $args = $this->evaluate($args);

        $entityType = $args[0] ?? null;
        $id = $args[1] ?? null;
        $workflowId = $args[2] ?? null;

        if (!$entityType) {
            throw new Error("No entity type.");
        }

        if (!$id) {
            throw new Error("No ID.");
        }

        if (!$workflowId) {
            throw new Error("No workflowId.");
        }

        $entity = $this->entityManager->getEntityById($entityType, $id);

        if (!$entity) {
            throw new Error("Entity $entityType $id not found.");
        }

        $this->getWorkflowService()->triggerWorkflow($entity, $workflowId, true);
    }

    private function getWorkflowService(): Service
    {
        return $this->injectableFactory->create(Service::class);
    }
}
