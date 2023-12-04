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

use Espo\Core\Exceptions\Error;
use Espo\Core\Exceptions\Forbidden;
use Espo\Core\Utils\Metadata;
use Espo\Entities\User;
use Espo\Modules\Advanced\Entities\Report;
use Espo\Modules\Advanced\Tools\Report\Service;
use Espo\ORM\EntityManager;

/**
 * @deprecated As of v7.5 PrimaryFilter is used.
 */
class ReportFilter
{
    private EntityManager $entityManager;
    private Metadata $metadata;
    private User $user;
    private Service $service;

    public function __construct(
        EntityManager $entityManager,
        Metadata $metadata,
        User $user,
        Service $service
    ) {
        $this->entityManager = $entityManager;
        $this->metadata = $metadata;
        $this->user = $user;
        $this->service = $service;
    }

    /**
     * @throws Forbidden
     * @throws Error
     */
    public function applyFilter(string $entityType, string $filterName, &$result, $selectManger)
    {
        $reportFilterId = $this->metadata
            ->get(['entityDefs', $entityType, 'collection', 'filters', $filterName, 'id']);

        if (!$reportFilterId) {
            throw new Error('Report Filter error.');
        }

        $reportFilter = $this->entityManager->getEntity('ReportFilter', $reportFilterId);

        if (!$reportFilter) {
            throw new Error('Report Filter not found.');
        }

        $teamIdList = $reportFilter->getLinkMultipleIdList('teams');

        if (count($teamIdList) && !$this->user->isAdmin()) {
            $isInTeam = false;
            $userTeamIdList = $this->user->getLinkMultipleIdList('teams');

            foreach ($userTeamIdList as $teamId) {
                if (in_array($teamId, $teamIdList)) {
                    $isInTeam = true;
                    break;
                }
            }

            if (!$isInTeam) {
                throw new Forbidden("Access denied to Report Filter.");
            }
        }

        $reportId = $reportFilter->get('reportId');

        if (!$reportId) {
            throw new Error('Report Filter error.');
        }

        /** @var ?Report $report */
        $report = $this->entityManager->getEntity(Report::ENTITY_TYPE, $reportId);

        if (!$report) {
            throw new Error('Report Filter error. Report not found.');
        }

        $selectParams = $this->service
            ->prepareSelectBuilder($report)
            ->build()
            ->getRaw();

        $result['whereClause'][] = $selectParams['whereClause'];

        foreach ($selectParams['joins'] ?? [] as $join) {
            $selectManger->addJoin($join, $result);
        }

        foreach ($selectParams['leftJoins'] ?? [] as $join) {
            $selectManger->addLeftJoin($join, $result);
        }

        if (!empty($selectParams['distinct'])) {
            $selectManger->setDistinct(true, $result);
        }
    }
}
