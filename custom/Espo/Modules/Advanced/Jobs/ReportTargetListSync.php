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

namespace Espo\Modules\Advanced\Jobs;

use Espo\Core\Job\JobDataLess;
use Espo\Core\Utils\Log;
use Espo\Modules\Advanced\Tools\Report\TargetListSyncService;
use Espo\Modules\Crm\Entities\TargetList;
use Espo\ORM\EntityManager;

use Exception;

class ReportTargetListSync implements JobDataLess
{
    private TargetListSyncService $service;
    private EntityManager $entityManager;
    private Log $log;

    public function __construct(
        TargetListSyncService $service,
        EntityManager $entityManager,
        Log $log
    ) {
        $this->service = $service;
        $this->entityManager = $entityManager;
        $this->log = $log;
    }

    public function run(): void
    {
        $targetListList = $this->entityManager
            ->getRDBRepository(TargetList::ENTITY_TYPE)
            ->where(['syncWithReportsEnabled' => true])
            ->find();

        foreach ($targetListList as $targetList) {
            try {
                $this->service->syncTargetListWithReports($targetList);
            }
            catch (Exception $e) {
                $this->log->error("ReportTargetListSync: {$e->getCode()}, {$e->getMessage()}");
            }
        }
    }
}
