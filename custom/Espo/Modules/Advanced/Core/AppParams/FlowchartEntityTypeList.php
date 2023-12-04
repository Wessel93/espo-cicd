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

namespace Espo\Modules\Advanced\Core\AppParams;

use Espo\Core\Acl;
use Espo\Core\Select\SelectBuilderFactory;
use Espo\Modules\Advanced\Entities\BpmnFlowchart;
use Espo\Modules\Advanced\Entities\BpmnProcess;
use Espo\ORM\EntityManager;

class FlowchartEntityTypeList
{
    private Acl $acl;
    private EntityManager $entityManager;
    private SelectBuilderFactory $selectBuilderFactory;

    public function __construct(
        Acl $acl,
        EntityManager $entityManager,
        SelectBuilderFactory $selectBuilderFactory
    ) {
        $this->acl = $acl;
        $this->entityManager = $entityManager;
        $this->selectBuilderFactory = $selectBuilderFactory;
    }

    /**
     * @return string[]
     */
    public function get(): array
    {
        if (!$this->acl->checkScope(BpmnProcess::ENTITY_TYPE, Acl\Table::ACTION_CREATE)) {
            return [];
        }

        if (!$this->acl->checkScope(BpmnFlowchart::ENTITY_TYPE, Acl\Table::ACTION_READ)) {
            return [];
        }

        $list = [];

        $query = $this->selectBuilderFactory
            ->create()
            ->from(BpmnFlowchart::ENTITY_TYPE)
            ->withAccessControlFilter()
            ->build();

        $collection = $this->entityManager
            ->getRDBRepository(BpmnFlowchart::ENTITY_TYPE)
            ->clone($query)
            ->select(['targetType'])
            ->group('targetType')
            ->where(['isActive' => true])
            ->find();

        foreach ($collection as $item) {
            $list[] = $item->get('targetType');
        }

        return $list;
    }
}
