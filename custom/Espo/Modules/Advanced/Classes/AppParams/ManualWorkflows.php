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

namespace Espo\Modules\Advanced\Classes\AppParams;

use Espo\Entities\User;
use Espo\Modules\Advanced\Entities\Workflow;
use Espo\ORM\Collection;
use Espo\ORM\EntityManager;
use stdClass;

class ManualWorkflows
{
    private EntityManager $entityManager;
    private User $user;

    public function __construct(
        EntityManager $entityManager,
        User $user
    ) {
        $this->entityManager = $entityManager;
        $this->user = $user;
    }

    /**
     * @return stdClass
     */
    public function get()
    {
        $data = (object) [];

        $builder = $this->entityManager
            ->getRDBRepository(Workflow::ENTITY_TYPE)
            ->where([
                'type' => Workflow::TYPE_MANUAL,
                'isActive' => true,
            ]);

        if (!$this->user->isAdmin()) {
            $builder
                ->distinct()
                ->join('manualTeams')
                ->where(['manualTeams.id' => $this->user->getTeamIdList()]);

            $builder->where(['manualAccessRequired!=' => 'admin']);
        }

        /** @var Collection<Workflow> $workflows */
        $workflows = $builder->find();

        foreach ($workflows as $workflow) {
            $entityType = $workflow->getTargetEntityType();

            if (!property_exists($data, $entityType)) {
                $data->$entityType = [];
            }

            $item = (object) [
                'id' => $workflow->getId(),
                'label' => $workflow->get('manualLabel'),
                'accessRequired' => $workflow->get('manualAccessRequired'),
                'elementType' => $workflow->get('manualElementType'),
                'dynamicLogic' => $workflow->get('manualDynamicLogic'),
            ];

            $data->$entityType[] = $item;
        }

        return $data;
    }
}
