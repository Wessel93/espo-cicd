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

namespace Espo\Modules\Advanced\Core;

use Espo\Core\Utils\DateTime;
use Espo\Modules\Advanced\Entities\BpmnSignalListener;
use Espo\ORM\EntityManager;
use Espo\ORM\Entity;
use Espo\Core\Utils\Config;

class SignalManager
{
    private EntityManager $entityManager;
    private WorkflowManager $workflowManager;
    private Config $config;

    public function __construct(
        EntityManager $entityManager,
        WorkflowManager $workflowManager,
        Config $config
    ) {
        $this->entityManager = $entityManager;
        $this->workflowManager = $workflowManager;
        $this->config = $config;
    }

    public function trigger($signal, ?Entity $entity = null, array $options = []): void
    {
        if (is_array($signal)) {
            $signal = implode('.', $signal);
        }

        if ($this->config->get('signalsDisabled')) {
            return;
        }

        if ($entity) {
            $this->workflowManager->process($entity, '$' . $signal, $options);

            return;
        }

        if ($this->config->get('signalsRegularDisabled')) {
            return;
        }

        $listenerList = $this->entityManager
            ->getRDBRepository(BpmnSignalListener::ENTITY_TYPE)
            ->select(['id'])
            ->order('number')
            ->where([
                'name' => $signal,
                'isTriggered' => false,
            ])
            ->find();

        foreach ($listenerList as $item) {
            $item->set('isTriggered', true);
            $item->set('triggeredAt', date(DateTime::SYSTEM_DATE_TIME_FORMAT));

            $this->entityManager->saveEntity($item);
        }
    }

    public function subscribe(string $signal, string $flowNodeId): ?string
    {
        if ($this->config->get('signalsDisabled')) {
            return null;
        }

        if ($this->config->get('signalsRegularDisabled')) {
            return null;
        }

        $item = $this->entityManager->createEntity(BpmnSignalListener::ENTITY_TYPE, [
            'name' => $signal,
            'flowNodeId' => $flowNodeId,
        ]);

        return $item->getId();
    }

    public function unsubscribe(string $id): void
    {
        $this->entityManager
            ->getRepository(BpmnSignalListener::ENTITY_TYPE)
            ->deleteFromDb($id);
    }
}
