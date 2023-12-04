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

namespace Espo\Modules\Advanced\Hooks\Workflow;

use Espo\Entities\Job;
use Espo\Modules\Advanced\Entities\Workflow;
use Espo\Modules\Advanced\Tools\Workflow\Jobs\RunScheduledWorkflow as RunScheduledWorkflowJob;
use Espo\ORM\Entity;
use Espo\ORM\EntityManager;

class RemoveJobs
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Workflow $entity
     */
    public function afterSave(Entity $entity): void
    {
        $toRemove = $entity->getType() === Workflow::TYPE_SCHEDULED &&
            (
                $entity->isAttributeChanged('scheduling') ||
                $entity->isAttributeChanged('schedulingApplyTimezone')
            );

        if (!$toRemove) {
            return;
        }

        $pendingJobList = $this->entityManager
            ->getRDBRepository(Job::ENTITY_TYPE)
            ->where([
                'className' => RunScheduledWorkflowJob::class,
                'status' => 'Pending',
                'targetType' => Workflow::ENTITY_TYPE,
                'targetId' => $entity->getId(),
            ])
            ->find();

        foreach ($pendingJobList as $pendingJob) {
            $this->entityManager->removeEntity($pendingJob);
        }
    }
}
