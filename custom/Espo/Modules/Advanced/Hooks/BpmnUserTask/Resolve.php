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

namespace Espo\Modules\Advanced\Hooks\BpmnUserTask;

use Espo\Core\InjectableFactory;
use Espo\Modules\Advanced\Core\Bpmn\BpmnManager;
use Espo\Modules\Advanced\Entities\BpmnFlowNode;
use Espo\Modules\Advanced\Entities\BpmnUserTask;
use Espo\ORM\Entity;
use Espo\ORM\EntityManager;

class Resolve
{
    private EntityManager $entityManager;
    private InjectableFactory $injectableFactory;

    public function __construct(
        EntityManager $entityManager,
        InjectableFactory $injectableFactory
    ) {
        $this->entityManager = $entityManager;
        $this->injectableFactory = $injectableFactory;
    }

    /**
     * @param BpmnUserTask $entity
     */
    public function afterSave(Entity $entity): void
    {
        $flowNodeId = $entity->get('flowNodeId');

        if (!$flowNodeId) {
            return;
        }

        if (!$entity->getFetched('isResolved') && $entity->get('isResolved')) {
            /** @var ?BpmnFlowNode $flowNode */
            $flowNode = $this->entityManager->getEntityById(BpmnFlowNode::ENTITY_TYPE, $flowNodeId);

            if (!$flowNode) {
                return;
            }

            if ($flowNode->getStatus() !== BpmnFlowNode::STATUS_IN_PROCESS) {
                return;
            }

            $manager = $this->injectableFactory->create(BpmnManager::class);

            $manager->completeFlow($flowNode);
        }
    }
}
