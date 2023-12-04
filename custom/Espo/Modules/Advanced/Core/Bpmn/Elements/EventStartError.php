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

namespace Espo\Modules\Advanced\Core\Bpmn\Elements;

use Espo\Modules\Advanced\Entities\BpmnFlowNode;

class EventStartError extends Base
{
    public function process(): void
    {
        $this->writeErrorData();
        $this->storeErrorVariables();
        $this->processNextElement();
    }

    private function writeErrorData(): void
    {
        $flowNode = $this->getFlowNode();

        $parentFlowNodeId = $this->getProcess()->getParentProcessFlowNodeId();

        if (!$parentFlowNodeId) {
            return;
        }

        /** @var ?BpmnFlowNode $parentFlowNode */
        $parentFlowNode = $this->getEntityManager()->getEntityById(BpmnFlowNode::ENTITY_TYPE, $parentFlowNodeId);

        if (!$parentFlowNode) {
            return;
        }

        $code = $parentFlowNode->getDataItemValue('caughtErrorCode');
        $message = $parentFlowNode->getDataItemValue('caughtErrorMessage');

        $flowNode->setDataItemValue('code', $code);
        $flowNode->setDataItemValue('message', $message);

        $this->getEntityManager()->saveEntity($flowNode);
    }

    private function storeErrorVariables(): void
    {
        $variables = $this->getVariables();

        $variables->__caughtErrorCode = $this->getFlowNode()->getDataItemValue('code');
        $variables->__caughtErrorMessage = $this->getFlowNode()->getDataItemValue('message');

        $this->getProcess()->setVariables($variables);
        $this->saveProcess();
    }
}
