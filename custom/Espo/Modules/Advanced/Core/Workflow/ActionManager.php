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

namespace Espo\Modules\Advanced\Core\Workflow;

use Espo\Core\Exceptions\Error;

use Exception;
use stdClass;

class ActionManager extends BaseManager
{
    protected string $dirName = 'Actions';
    protected array $requiredOptions = ['type'];

    /**
     * @param stdClass[] $actions
     * @throws Error
     */
    public function runActions(array $actions): void
    {
        if (!isset($actions)) {
            return;
        }

        $this->getLog()->debug('Workflow\ActionManager: Start workflow rule ID ['.$this->getWorkflowId().'].');

        $variables = (object) [];

        // Should be initialized before the loop.
        $processId = $this->getProcessId();

        foreach ($actions as $action) {
            $this->runAction($action, $processId, $variables);
        }

        $this->getLog()->debug('Workflow\ActionManager: End workflow rule ID ['.$this->getWorkflowId().'].');
    }

    /**
     * @throws Error
     */
    private function runAction(stdClass $actionData, ?string $processId, stdClass $variables): void
    {
        $entity = $this->getEntity($processId);

        $entityType = $entity->getEntityType();

        if (!$this->validate($actionData)) {
            $GLOBALS['log']->warning(
                'Workflow['.$this->getWorkflowId($processId).']: Action data is broken for the Entity ['.
                $entityType.'].');

            return;
        }

        $actionImpl = $this->getImpl($actionData->type, $processId);

        if (!isset($actionImpl)) {
            return;
        }

        try {
            $actionImpl->process($entity, $actionData, null, $variables);

            $this->copyVariables($actionImpl->getVariablesBack(), $variables);
        }
        catch (Exception $e) {
            $GLOBALS['log']->error(
                'Workflow[' . $this->getWorkflowId($processId) . ']: Action failed [' .
                $actionData->type . '] with cid [' .
                $actionData->cid . '], details: ' . $e->getMessage() . '.');
        }
    }

    private function copyVariables(object $source, object $destination)
    {
        foreach (get_object_vars($destination) as $k => $v) {
            unset($destination->$k);
        }

        foreach (get_object_vars($source) as $k => $v) {
            $destination->$k = $v;
        }
    }
}
