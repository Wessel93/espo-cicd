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

namespace Espo\Modules\Advanced\Services;

use Espo\Entities\EmailAddress;
use Espo\ORM\Entity;
use Espo\ORM\EntityManager;
use Espo\Repositories\EmailAddress as EmailAddressRepository;

use stdClass;

class TargetListWorkflow
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function optOut(?string $workflowId, Entity $entity, stdClass $data)
    {
        $targetListId = $data->targetListId ?? null;

        if ($targetListId) {
            $this->entityManager
                ->getRDBRepository($entity->getEntityType())
                ->getRelation($entity, 'targetLists')
                ->updateColumnsById($targetListId, ['optedOut' => true]);

            return;
        }

        $emailAddress = $entity->get('emailAddress');

        if ($emailAddress) {
            /** @var EmailAddressRepository $emailAddressRepository */
            $emailAddressRepository = $this->entityManager->getRepository(EmailAddress::ENTITY_TYPE);

            $addressEntity = $emailAddressRepository->getByAddress($emailAddress);

            if ($addressEntity) {
                $addressEntity->set('optOut', true);
                $this->entityManager->saveEntity($addressEntity);
            }
        }
    }
}
