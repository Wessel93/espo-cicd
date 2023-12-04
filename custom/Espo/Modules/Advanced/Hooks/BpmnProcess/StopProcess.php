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

namespace Espo\Modules\Advanced\Hooks\BpmnProcess;

use Espo\Core\InjectableFactory;
use Espo\Modules\Advanced\Core\Bpmn\BpmnManager;
use Espo\Modules\Advanced\Entities\BpmnProcess;
use Espo\ORM\Entity;
use Espo\ORM\EntityManager;

class StopProcess
{
    private InjectableFactory $injectableFactory;
    private EntityManager $entityManager;

    public function __construct(
        InjectableFactory $injectableFactory,
        EntityManager $entityManager
    ) {
        $this->injectableFactory = $injectableFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @param BpmnProcess $entity
     */
    public function afterSave(Entity $entity, array $options): void
    {
        if (!empty($options['skipStopProcess'])) {
            return;
        }

        if ($entity->isNew()) {
            return;
        }

        if (!$entity->isAttributeChanged('status')) {
            return;
        }

        if ($entity->getStatus() !== BpmnProcess::STATUS_STOPPED) {
            return;
        }

        $manager = $this->injectableFactory->create(BpmnManager::class);

        $manager->stopProcess($entity);

        $subProcessList = $this->entityManager
            ->getRDBRepository(BpmnProcess::ENTITY_TYPE)
            ->where(['parentProcessId' => $entity->getId()])
            ->find();

        foreach ($subProcessList as $e) {
            $manager->stopProcess($e);
        }
    }
}
