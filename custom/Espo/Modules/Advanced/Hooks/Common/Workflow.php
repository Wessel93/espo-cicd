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

namespace Espo\Modules\Advanced\Hooks\Common;

use Espo\Modules\Advanced\Core\WorkflowManager;
use Espo\ORM\Entity;

class Workflow
{
    public static $order = 99;

    private WorkflowManager $workflowManager;

    public function __construct(WorkflowManager $workflowManager) {
        $this->workflowManager = $workflowManager;
    }

    public function afterSave(Entity $entity, array $options): void
    {
        if (!empty($options['skipWorkflow'])) {
            return;
        }

        if (!empty($options['silent'])) {
            return;
        }

        if ($entity->isNew()) {
            $this->workflowManager->process($entity, WorkflowManager::AFTER_RECORD_CREATED, $options);
        } else {
            $this->workflowManager->process($entity, WorkflowManager::AFTER_RECORD_UPDATED, $options);
        }

        $this->workflowManager->process($entity, WorkflowManager::AFTER_RECORD_SAVED, $options);
    }
}
