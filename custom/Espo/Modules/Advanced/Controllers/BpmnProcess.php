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

namespace Espo\Modules\Advanced\Controllers;

use Espo\Core\Acl\Table;
use Espo\Core\Api\Request;
use Espo\Core\Controllers\Record;
use Espo\Core\Exceptions\BadRequest;
use Espo\Core\Exceptions\Error;
use Espo\Core\Exceptions\Forbidden;
use Espo\Core\Exceptions\NotFound;
use Espo\Modules\Advanced\Entities\BpmnProcess as BpmnProcessEntity;
use Espo\Modules\Advanced\Services\BpmnProcess as BpmnProcessService;

class BpmnProcess extends Record
{
    /**
     * @throws BadRequest
     * @throws Forbidden
     * @throws Error
     * @throws NotFound
     */
    public function postActionStop(Request $request): bool
    {
        $id = $request->getParsedBody()->id ?? null;

        if (!$id) {
            throw new BadRequest();
        }

        if (!$this->acl->checKScope(BpmnProcessEntity::ENTITY_TYPE, Table::ACTION_EDIT)) {
            throw new Forbidden();
        }

        /** @var BpmnProcessService $service */
        $service = $this->getRecordService();

        $service->stopProcess($id);

        return true;
    }

    /**
     * @throws BadRequest
     * @throws Forbidden
     * @throws Error
     * @throws NotFound
     */
    public function postActionReactivate(Request $request): bool
    {
        $id = $request->getParsedBody()->id ?? null;

        if (!$id) {
            throw new BadRequest();
        }

        if (!$this->acl->checKScope(BpmnProcessEntity::ENTITY_TYPE, Table::ACTION_EDIT)) {
            throw new Forbidden();
        }

        /** @var BpmnProcessService $service */
        $service = $this->getRecordService();

        $service->reactivateProcess($id);

        return true;
    }

    /**
     * @throws BadRequest
     * @throws Forbidden
     * @throws NotFound
     */
    public function postActionRejectFlowNode(Request $request): bool
    {
        $id = $request->getParsedBody()->id ?? null;

        if (!$id) {
            throw new BadRequest();
        }

        if (!$this->acl->checKScope(BpmnProcessEntity::ENTITY_TYPE, Table::ACTION_EDIT)) {
            throw new Forbidden();
        }

        /** @var BpmnProcessService $service */
        $service = $this->getRecordService();

        $service->rejectFlowNode($id);

        return true;
    }

    /**
     * @throws BadRequest
     * @throws Forbidden
     * @throws Error
     * @throws NotFound
     */
    public function postActionStartFlowFromElement(Request $request): bool
    {
        $processId = $request->getParsedBody()->processId ?? null;
        $elementId = $request->getParsedBody()->elementId ?? null;

        if (!$processId) {
            throw new BadRequest();
        }

        if (!$elementId) {
            throw new BadRequest();
        }

        if (!$this->acl->checKScope(BpmnProcessEntity::ENTITY_TYPE, Table::ACTION_EDIT)) {
            throw new Forbidden();
        }

        /** @var BpmnProcessService $service */
        $service = $this->getRecordService();

        $service->startFlowFromElement($processId, $elementId);

        return true;
    }
}
