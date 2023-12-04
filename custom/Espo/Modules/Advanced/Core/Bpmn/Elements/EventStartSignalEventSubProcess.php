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

use Espo\Modules\Advanced\Core\Bpmn\Utils\Helper;
use Espo\Modules\Advanced\Core\SignalManager;
use Espo\Modules\Advanced\Entities\BpmnFlowNode;

class EventStartSignalEventSubProcess extends Event
{
    /**
     * @param string|bool|null $divergentFlowNodeId
     */
    protected function processNextElement(
        ?string $nextElementId = null,
        $divergentFlowNodeId = false,
        bool $dontSetProcessed = false
    ): ?BpmnFlowNode {

        return parent::processNextElement($this->getFlowNode()->getDataItemValue('subProcessElementId'));
    }

    public function process(): void
    {
        $signal = $this->getSignal();

        if (!$signal) {
            $this->fail();

            $GLOBALS['log']->warning("BPM: No signal for sub-process start event");

            return;
        }

        $flowNode = $this->getFlowNode();
        $flowNode->set([
            'status' => BpmnFlowNode::STATUS_STANDBY,
        ]);
        $this->getEntityManager()->saveEntity($flowNode);

        $this->getSignalManager()->subscribe($signal, $flowNode->get('id'));
    }

    public function proceedPending(): void
    {
        $subProcessIsInterrupting = $this->getFlowNode()->getDataItemValue('subProcessIsInterrupting');

        if (!$subProcessIsInterrupting) {
            $this->createCopy();
        }

        if ($subProcessIsInterrupting) {
            $this->getManager()->interruptProcessByEventSubProcess($this->getProcess(), $this->getFlowNode());
        }

        $this->processNextElement();
    }

    protected function createCopy(): void
    {
        $data = $this->getFlowNode()->get('data') ?? (object) [];
        $data = clone $data;

        $flowNode = $this->getEntityManager()->getEntity(BpmnFlowNode::ENTITY_TYPE);

        $flowNode->set([
            'status' => BpmnFlowNode::STATUS_STANDBY,
            'elementType' => $this->getFlowNode()->getElementType(),
            'elementData' => $this->getFlowNode()->get('elementData'),
            'data' => $data,
            'flowchartId' => $this->getProcess()->getFlowchartId(),
            'processId' => $this->getProcess()->get('id'),
            'targetType' => $this->getFlowNode()->getTargetType(),
            'targetId' => $this->getFlowNode()->getTargetId(),
        ]);

        $this->getEntityManager()->saveEntity($flowNode);

        $this->getSignalManager()->subscribe($this->getSignal(), $flowNode->get('id'));
    }

    protected function getSignal() : ?string
    {
        $subProcessStartData = $this->getFlowNode()->getDataItemValue('subProcessStartData') ?? (object) [];

        $name = $subProcessStartData->signal ?? null;

        if (!$name) {
            return $name;
        }

        return Helper::applyPlaceholders($name, $this->getTarget(), $this->getVariables());
    }

    protected function getSignalManager(): SignalManager
    {
        /** @var SignalManager */
        return $this->getContainer()->get('signalManager');
    }
}