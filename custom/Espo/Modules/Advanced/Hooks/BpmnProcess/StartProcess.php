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

class StartProcess
{
    private InjectableFactory $injectableFactory;

    public function __construct(
        InjectableFactory $injectableFactory
    ) {
        $this->injectableFactory = $injectableFactory;
    }

    /**
     * @param BpmnProcess $entity
     */
    public function afterSave(Entity $entity, array $options): void
    {
        if (!$entity->isNew()) {
            return;
        }

        if (!empty($options['skipStartProcessFlow'])) {
            return;
        }

        $manager = $this->injectableFactory->create(BpmnManager::class);

        $manager->startCreatedProcess($entity);
    }
}
