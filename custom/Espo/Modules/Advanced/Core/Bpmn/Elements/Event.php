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

abstract class Event extends Base
{
    protected function rejectConcurrentPendingFlows()
    {
        $flowNode = $this->getFlowNode();

        if ($flowNode->get('previousFlowNodeElementType') === 'gatewayEventBased') {
            $concurrentFlowNodeList = $this->getEntityManager()
                ->getRepository(BpmnFlowNode::ENTITY_TYPE)
                ->where([
                    'previousFlowNodeElementType' => 'gatewayEventBased',
                    'previousFlowNodeId' => $flowNode->get('previousFlowNodeId'),
                    'processId' => $flowNode->getProcessId(),
                    'id!=' => $flowNode->get('id'),
                    'status' => BpmnFlowNode::STATUS_PENDING,
                ])
                ->find();

            foreach ($concurrentFlowNodeList as $concurrentFlowNode) {
                $concurrentFlowNode->set('status', BpmnFlowNode::STATUS_REJECTED);

                $this->getEntityManager()->saveEntity($concurrentFlowNode);
            }
        }
    }
}
