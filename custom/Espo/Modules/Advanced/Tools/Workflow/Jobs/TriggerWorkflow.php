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

namespace Espo\Modules\Advanced\Tools\Workflow\Jobs;

use Espo\Core\Exceptions\Error;
use Espo\Core\Job\Job;
use Espo\Core\Job\Job\Data;
use Espo\Modules\Advanced\Tools\Workflow\Service;
use Espo\ORM\EntityManager;

class TriggerWorkflow implements Job
{
    private Service $service;
    private EntityManager $entityManager;

    public function __construct(
        Service $service,
        EntityManager $entityManager
    ) {
        $this->service = $service;
        $this->entityManager = $entityManager;
    }

    public function run(Data $data): void
    {
        $data = $data->getRaw();

        if (
            empty($data->entityId) ||
            empty($data->entityType) ||
            empty($data->nextWorkflowId)
        ) {
            throw new Error('Workflow[' . $data->workflowId . '][triggerWorkflow]: Not sufficient job data.');
        }

        $entityId = $data->entityId;
        $entityType = $data->entityType;

        $entity = $this->entityManager->getEntityById($entityType, $entityId);

        if (!$entity) {
            throw new Error('Workflow[' . $data->workflowId . '][triggerWorkflow]: Entity not found.');
        }

        if (is_array($data->values)) {
            $data->values = (object) $data->values;
        }

        if (is_object($data->values)) {
            $values = get_object_vars($data->values);

            foreach ($values as $attribute => $value) {
                $entity->setFetched($attribute, $value);
            }
        }

        $this->service->triggerWorkflow($entity, $data->nextWorkflowId, true);
    }
}
