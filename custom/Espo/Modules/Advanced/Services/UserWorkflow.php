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
use Espo\Core\Record\ServiceContainer;
use Espo\ORM\Entity;
use Espo\Tools\UserSecurity\Password\Service;

class UserWorkflow
{
    private InjectableFactory $injectableFactory;
    private ServiceContainer $serviceContainer;

    public function __construct(
        InjectableFactory $injectableFactory,
        ServiceContainer $serviceContainer
    ) {
        $this->injectableFactory = $injectableFactory;
        $this->serviceContainer = $serviceContainer;
    }

    public function generateAndSendPassword(?string $workflowId, Entity $entity, $data): void
    {
        if (class_exists("Espo\\Tools\\UserSecurity\\Password\\Service")) {
            /** @var Service $service */
            $service = $this->injectableFactory->create("Espo\\Tools\\UserSecurity\\Password\\Service");

            // @todo Support non-admin users.

            $service->generateAndSendNewPasswordForUser($entity->getId());

            return;
        }

        $service = $this->serviceContainer->get('User');

        if (method_exists($service, 'generateNewPasswordForUser')) {
            $service->generateNewPasswordForUser($entity->getId(), true);
        }
    }
}
