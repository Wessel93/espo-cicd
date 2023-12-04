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

use Espo\Core\InjectableFactory;
use Espo\Entities\User;
use Espo\Modules\Crm\Business\Event\Invitations;
use Espo\ORM\Entity;
use Espo\ORM\EntityManager;

class EventInvitationsAdvanced
{
    private InjectableFactory $injectableFactory;
    private EntityManager $entityManager;
    private User $user;

    public function __construct(
        InjectableFactory $injectableFactory,
        EntityManager $entityManager,
        User $user
    ) {
        $this->injectableFactory = $injectableFactory;
        $this->entityManager = $entityManager;
        $this->user = $user;
    }

    private function getInvitationManager(): Invitations
    {
        return $this->injectableFactory->create(Invitations::class);
    }

    public function sendInvitationsAction(?string $workflowId, Entity $entity): void
    {
        $invitationManager = $this->getInvitationManager();
        $emailHash = [];
        $sentCount = 0;

        $users = $this->entityManager
            ->getRDBRepository($entity->getEntityType())
            ->getRelation($entity, 'users')
            ->find();

        foreach ($users as $user) {
            if ($user->getId() === $this->user->getId()) {
                if (
                    $entity->getLinkMultipleColumn('users', 'status', $user->getId()) ===
                    'Accepted'
                ) {
                    continue;
                }
            }
            if ($user->get('emailAddress') && !array_key_exists($user->get('emailAddress'), $emailHash)) {
                $invitationManager->sendInvitation($entity, $user, 'users');
                $emailHash[$user->get('emailAddress')] = true;

                $sentCount++;
            }
        }

        $contacts = $this->entityManager
            ->getRDBRepository($entity->getEntityType())
            ->getRelation($entity, 'contacts')
            ->find();

        foreach ($contacts as $contact) {
            if ($contact->get('emailAddress') && !array_key_exists($contact->get('emailAddress'), $emailHash)) {
                $invitationManager->sendInvitation($entity, $contact, 'contacts');
                $emailHash[$contact->get('emailAddress')] = true;

                $sentCount++;
            }
        }

        $leads = $this->entityManager
            ->getRDBRepository($entity->getEntityType())
            ->getRelation($entity, 'leads')
            ->find();

        foreach ($leads as $lead) {
            if ($lead->get('emailAddress') && !array_key_exists($lead->get('emailAddress'), $emailHash)) {
                $invitationManager->sendInvitation($entity, $lead, 'leads');
                $emailHash[$lead->get('emailAddress')] = true;

                $sentCount++;
            }
        }
    }
}
