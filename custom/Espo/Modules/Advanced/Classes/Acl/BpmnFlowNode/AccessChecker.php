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

namespace Espo\Modules\Advanced\Classes\Acl\BpmnFlowNode;

use Espo\Core\Acl\AccessChecker as AccessCheckerInterface;
use Espo\Core\Acl\AccessReadChecker;
use Espo\Core\Acl\ScopeData;
use Espo\Core\AclManager;
use Espo\Entities\User;
use Espo\Modules\Advanced\Entities\BpmnProcess;

class AccessChecker implements AccessCheckerInterface, AccessReadChecker
{
    private AclManager $aclManager;

    public function __construct(AclManager $aclManager)
    {
        $this->aclManager = $aclManager;
    }

    public function check(User $user, ScopeData $data): bool
    {
        return $this->aclManager->checkScope($user, BpmnProcess::ENTITY_TYPE);
    }

    public function checkRead(User $user, ScopeData $data): bool
    {
        return false;
    }
}
