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

use Espo\Entities\Job;
use Espo\Modules\Advanced\Entities\Workflow;
use Espo\Modules\Advanced\Tools\Workflow\Jobs\TriggerWorkflow as TriggerWorkflowJob;
use Espo\Modules\Advanced\Tools\Workflow\Service;
use Espo\ORM\Entity;
use stdClass;

class TriggerWorkflow extends Base
{
    protected function run(Entity $entity, stdClass $actionData): bool
    {
        if (empty($actionData->workflowId)) {
            return false;
        }

        $targetEntity = $entity;

        if (!empty($actionData->target)) {
            $target = $actionData->target;

            $targetEntity = $this->getTargetEntityFromTargetItem($entity, $target);
        }

        if ($targetEntity) {
            $this->triggerAnotherWorkflow($targetEntity, $actionData);
        }

        return true;
    }

    private function triggerAnotherWorkflow(Entity $entity, stdClass $actionData): void
    {
        $jobData = [
            'workflowId' => $this->getWorkflowId(),
            'entityId' => $entity->getId(),
            'entityType' => $entity->getEntityType(),
            'nextWorkflowId' => $actionData->workflowId,
            'values' => $entity->getValueMap(),
        ];

        /** @var ?Workflow $workflow */
        $workflow = $this->getEntityManager()->getEntityById(Workflow::ENTITY_TYPE, $actionData->workflowId);

        if (!$workflow) {
            return;
        }

        if ($entity->getEntityType() !== $workflow->getTargetEntityType()) {
            return;
        }

        $executeTime = null;

        if (
            property_exists($actionData, 'execution') &&
            property_exists($actionData->execution, 'type')
        ) {
            $executeType = $actionData->execution->type;
        }

        if (isset($executeType) && $executeType !== 'immediately') {
            $executeTime = $this->getExecuteTime($actionData);
        }

        if ($executeTime) {
            $job = $this->getEntityManager()->getEntity(Job::ENTITY_TYPE);

            $job->set([
                'name' => TriggerWorkflowJob::class,
                'className' => TriggerWorkflowJob::class,
                'data' => (object) $jobData,
                'executeTime' => $executeTime,
            ]);

            $this->getEntityManager()->saveEntity($job);

            return;
        }

        $service = $this->injectableFactory->create(Service::class);

        $service->triggerWorkflow($entity, $actionData->workflowId);
    }
}
