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

namespace Espo\Modules\Advanced\Core\Cleanup;

//use Espo\Core\Cleanup\Cleanup;
use Espo\Core\Utils\Config;
use Espo\ORM\EntityManager;
use DateTime;

class WorkflowLog /*implements Cleanup*/
{
    private EntityManager $entityManager;
    private Config $config;

    public function __construct(
        EntityManager $entityManager,
        Config $config
    ) {
        $this->entityManager= $entityManager;
        $this->config = $config;
    }

    public function process(): void
    {
        $period = '-' . $this->config->get('cleanupWorkflowLogPeriod', '2 months');
        $datetime = new DateTime();
        $datetime->modify($period);

        $deleteQuery = $this->entityManager
            ->getQueryBuilder()
            ->delete()
            ->from('WorkflowLogRecord')
            ->where(['createdAt<' => $datetime->format('Y-m-d H:i:s')])
            ->build();

        $this->entityManager
            ->getQueryExecutor()
            ->execute($deleteQuery);
    }
}
