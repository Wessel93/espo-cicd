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

namespace Espo\Modules\Advanced\Core\Bpmn\Utils\MessageSenders;

use Espo\Core\Container;
use Espo\Core\Exceptions\Error;
use Espo\Modules\Advanced\Core\Workflow\Actions\SendEmail;
use Espo\ORM\Entity;
use Espo\Modules\Advanced\Entities\BpmnProcess;
use Espo\Modules\Advanced\Entities\BpmnFlowNode;
use stdClass;

class EmailType
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function process(
        Entity $target,
        BpmnFlowNode $flowNode,
        BpmnProcess $process,
        stdClass $createdEntitiesData,
        stdClass $variables
    ): void {

        $elementData = $flowNode->get('elementData');

        if (empty($elementData->from)) {
            throw new Error("No 'from'.");
        }

        $from = $elementData->from;

        if (empty($elementData->to)) {
            throw new Error("No 'to'.");
        }

        $to = $elementData->to;

        $replyTo = null;

        if (!empty($elementData->replyTo)) {
            $replyTo = $elementData->replyTo;
        }

        if (empty($elementData->emailTemplateId)) {
            throw new Error("No 'emailTemplateId'.");
        }

        $emailTemplateId = $elementData->emailTemplateId;

        $doNotStore = false;

        if (isset($elementData->doNotStore)) {
            $doNotStore = $elementData->doNotStore;
        }

        $actionData = (object) [
            'type' => 'SendEmail',
            'from' => $from,
            'to' => $to,
            'replyTo' => $replyTo,
            'emailTemplateId' => $emailTemplateId,
            'doNotStore' => $doNotStore,
            'processImmediately' => true,
            'elementId' => $flowNode->get('elementId'),
            'optOutLink' => $elementData->optOutLink ?? false,
        ];

        if (property_exists($elementData, 'toEmailAddress')) {
            $actionData->toEmail = $elementData->toEmailAddress;
        }

        if (property_exists($elementData, 'fromEmailAddress')) {
            $actionData->fromEmail = $elementData->fromEmailAddress;
        }

        if (property_exists($elementData, 'replyToEmailAddress')) {
            $actionData->replyToEmail = $elementData->replyToEmailAddress;
        }

        if ($to && in_array($to, ['specifiedContacts', 'specifiedUsers', 'specifiedTeams'])) {
            $actionData->toSpecifiedEntityIds = $elementData->{'to' . ucfirst($to) . 'Ids'};
        }

        if ($replyTo && in_array($replyTo, ['specifiedContacts', 'specifiedUsers', 'specifiedTeams'])) {
            $actionData->replyToSpecifiedEntityIds = $elementData->{'replyTo' . ucfirst($replyTo) . 'Ids'};
        }

        $this->getActionImplementation()->process(
            $target,
            $actionData,
            $createdEntitiesData,
            $variables,
            $process
        );
    }

    private function getActionImplementation(): SendEmail
    {
        return new SendEmail($this->container);
    }
}
