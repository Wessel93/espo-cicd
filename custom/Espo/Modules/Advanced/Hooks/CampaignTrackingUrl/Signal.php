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

namespace Espo\Modules\Advanced\Hooks\CampaignTrackingUrl;

use Espo\Modules\Advanced\Core\SignalManager;
use Espo\ORM\Entity;
use Espo\ORM\EntityManager;

class Signal
{
    public static $order = 100;

    private EntityManager $entityManager;
    private SignalManager $signalManager;

    public function __construct(
        EntityManager $entityManager,
        SignalManager $signalManager
    ) {
        $this->entityManager = $entityManager;
        $this->signalManager = $signalManager;
    }

    public function afterClick(Entity $entity, array $options, array $hookData): void
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

        $uid = $hookData['uid'] ?? null;

        if ($uid) {
            $this->signalManager->trigger(
                implode('.', ['clickUniqueUrl', $uid])
            );
        }

        $targetType = $hookData['targetType'] ?? null;
        $targetId = $hookData['targetId'] ?? null;

        if (!$targetType || !$targetId) {
            return;
        }

        $target = $this->entityManager->getEntityById($targetType, $targetId);

        if (!$target) {
            return;
        }

        $signalManager = $this->signalManager;

        $signalManager->trigger(implode('.', ['@clickUrl', $entity->getId()]), $target);
        $signalManager->trigger(implode('.', ['@clickUrl']), $target);

        $signalManager->trigger(implode('.', ['clickUrl', $targetType, $targetId, $entity->getId()]));
        $signalManager->trigger(implode('.', ['clickUrl', $targetType, $targetId]));
    }
}
