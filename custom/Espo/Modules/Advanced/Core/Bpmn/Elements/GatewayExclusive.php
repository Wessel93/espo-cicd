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

namespace Espo\Modules\Advanced\Core\Bpmn\Elements;

use Espo\Modules\Advanced\Core\Bpmn\Utils\ConditionManager;

class GatewayExclusive extends Gateway
{
    protected function processDivergent(): void
    {
        $conditionManager = $this->getConditionManager();

        $flowList = $this->getAttributeValue('flowList');

        if (!is_array($flowList)) {
            $flowList = [];
        }

        $defaultNextElementId = $this->getAttributeValue('defaultNextElementId');
        $nextElementId = null;

        foreach ($flowList as $flowData) {
            $conditionsAll = isset($flowData->conditionsAll) ? $flowData->conditionsAll : null;
            $conditionsAny = isset($flowData->conditionsAny) ? $flowData->conditionsAny : null;
            $conditionsFormula = isset($flowData->conditionsFormula) ? $flowData->conditionsFormula : null;

            $result = $conditionManager->check(
                $this->getTarget(),
                $conditionsAll,
                $conditionsAny,
                $conditionsFormula,
                $this->getVariablesForFormula()
            );

            if ($result) {
                $nextElementId = $flowData->elementId;

                break;
            }
        }

        if (!$nextElementId && $defaultNextElementId) {
            $nextElementId = $defaultNextElementId;
        }

        if ($nextElementId) {
            $this->processNextElement($nextElementId);

            return;
        }

        $this->endProcessFlow();
    }

    protected function processConvergent(): void
    {
        $this->processNextElement();
    }

    protected function getConditionManager(): ConditionManager
    {
        $conditionManager = new ConditionManager($this->getContainer());
        $conditionManager->setCreatedEntitiesData($this->getCreatedEntitiesData());

        return $conditionManager;
    }
}