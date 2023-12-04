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

namespace Espo\Modules\Advanced\Hooks\TargetList;

use Espo\Modules\Advanced\Core\SignalManager;
use Espo\ORM\Entity;
use Espo\ORM\EntityManager;

class Signal
{
    public static $order = 100;

    private SignalManager $signalManager;
    private EntityManager $entityManager;

    public function __construct(
        SignalManager $signalManager,
        EntityManager $entityManager
    ) {
        $this->signalManager = $signalManager;
        $this->entityManager = $entityManager;
    }

    public function afterOptOut(Entity $entity, array $options, array $hookData): void
    {
        if (!empty($options['skipWorkflow'])) {
            return;
        }

        if (!empty($options['skipSignal'])) {
            return;
        }

        if (!empty($options['silent'])) {
            return;
        }

        $targetType = $hookData['targetType'];
        $targetId = $hookData['targetId'];

        $target = $this->entityManager->getEntityById($targetType, $targetId);

        if (!$target) {
            return;
        }

        $this->signalManager->trigger(implode('.', ['@optOut', $entity->getId()]), $target);
        $this->signalManager->trigger(
            implode('.', ['optOut', $target->getEntityType(), $target->getId(), $entity->getId()]));
    }

    public function afterCancelOptOut(Entity $entity, array $options, array $hookData): void
    {
        if (!empty($options['skipWorkflow'])) {
            return;
        }

        if (!empty($options['skipSignal'])) {
            return;
        }

        if (!empty($options['silent'])) {
            return;
        }

        $targetType = $hookData['targetType'];
        $targetId = $hookData['targetId'];

        $target = $this->entityManager->getEntityById($targetType, $targetId);

        if (!$target) {
            return;
        }

        $this->signalManager->trigger(implode('.', ['@cancelOptOut', $entity->getId()]), $target);
        $this->signalManager->trigger(
            implode('.', ['cancelOptOut', $target->getEntityType(), $target->getId(), $entity->getId()]));
    }
}
