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

namespace Espo\Modules\Advanced\Classes\Select\Common\PrimaryFilters;

use Espo\Core\Exceptions\Error;
use Espo\Core\Exceptions\Forbidden;
use Espo\Core\Select\Primary\Filter;
use Espo\Core\Utils\Metadata;
use Espo\Entities\User;
use Espo\Modules\Advanced\Entities\Report;
use Espo\Modules\Advanced\Entities\ReportFilter as ReportFilterEntity;
use Espo\Modules\Advanced\Tools\Report\Service;
use Espo\ORM\EntityManager;
use Espo\ORM\Query\SelectBuilder as QueryBuilder;
use RuntimeException;

class ReportFilter implements Filter
{
    private EntityManager $entityManager;
    private Metadata $metadata;
    private User $user;
    private Service $service;
    private string $name;
    private string $entityType;

    public function __construct(
        $name,
        $entityType,
        EntityManager $entityManager,
        Metadata $metadata,
        User $user,
        Service $service
    ) {
        $this->entityManager = $entityManager;
        $this->metadata = $metadata;
        $this->user = $user;
        $this->service = $service;
        $this->name = $name;
        $this->entityType = $entityType;
    }

    public function apply(QueryBuilder $queryBuilder): void
    {
        $reportFilterId = $this->metadata
            ->get(['entityDefs', $this->entityType, 'collection', 'filters', $this->name, 'id']);

        if (!$reportFilterId) {
            throw new RuntimeException("Report Filter $reportFilterId error.");
        }

        /** @var ?ReportFilterEntity $reportFilter */
        $reportFilter = $this->entityManager->getEntityById(ReportFilterEntity::ENTITY_TYPE, $reportFilterId);

        if (!$reportFilter) {
            throw new RuntimeException("Report Filter $reportFilterId not found.");
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
                throw new Forbidden("Access denied to Report Filter $reportFilterId.");
            }
        }

        $reportId = $reportFilter->get('reportId');

        if (!$reportId) {
            throw new RuntimeException('Report Filter error. No report.');
        }

        /** @var ?Report $report */
        $report = $this->entityManager->getEntityById(Report::ENTITY_TYPE, $reportId);

        if (!$report) {
            throw new Error('Report Filter error. Report not found.');
        }

        $query = $this->service
            ->prepareSelectBuilder($report)
            ->build();

        foreach ($query->getLeftJoins() as $join) {
            $queryBuilder->leftJoin($join);
        }

        foreach ($query->getJoins() as $join) {
            $queryBuilder->join($join);
        }

        if ($query->getWhere()) {
            $queryBuilder->where($query->getWhere());
        }

        if ($query->isDistinct()) {
            $queryBuilder->distinct();
        }
    }
}
