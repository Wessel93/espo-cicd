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
use Espo\Modules\Advanced\Entities\BpmnFlowchart;
use Espo\ORM\Entity;
use Espo\Modules\Advanced\Core\Bpmn\BpmnManager;
use stdClass;

class StartBpmnProcess extends Base
{
    protected function run(Entity $entity, stdClass $actionData): bool
    {
        if (!empty($actionData->target)) {
            $target = $actionData->target;
            $targetEntity = $this->getTargetEntityFromTargetItem($entity, $target);

            if (!$targetEntity) {
                $GLOBALS['log']->notice('Workflow StartBpmnProcess: Empty target.');

                return false;
            }
        }
        else {
            $targetEntity = $this->getEntityManager()->getEntityById($entity->getEntityType(), $entity->getId());
        }

        if (empty($actionData->flowchartId) || empty($actionData->elementId)) {
            throw new Error('StartBpmnProcess: Empty action data.');
        }

        $bpmnManager = new BpmnManager($this->getContainer());

        /** @var ?BpmnFlowchart $flowchart */
        $flowchart = $this->getEntityManager()->getEntityById(BpmnFlowchart::ENTITY_TYPE, $actionData->flowchartId);

        if (!$flowchart) {
            throw new Error('StartBpmnProcess: Could not find flowchart ' . $actionData->flowchartId . '.');
        }

        if ($flowchart->get('targetType') !== $targetEntity->getEntityType()) {
            throw new Error("Workflow StartBpmnProcess: Target entity type doesn't match flowchart target type.");
        }

        $bpmnManager->startProcess(
            $targetEntity,
            $flowchart,
            $actionData->elementId,
            null,
            $this->getWorkflowId()
        );

        return true;
    }
}
