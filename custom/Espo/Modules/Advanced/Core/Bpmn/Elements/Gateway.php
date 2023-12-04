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

abstract class Gateway extends Base
{
    public function process(): void
    {
        if ($this->isDivergent()) {
            $this->processDivergent();

            return;
        }

        if ($this->isConvergent()) {
            $this->processConvergent();

            return;
        }

        $this->setFailed();
    }

    abstract protected function processDivergent(): void;

    abstract protected function processConvergent(): void;

    protected function isDivergent(): bool
    {
        $nextElementIdList = $this->getAttributeValue('nextElementIdList') ?? [];

        return !$this->isConvergent() && count($nextElementIdList);
    }

    protected function isConvergent(): bool
    {
        $previousElementIdList = $this->getAttributeValue('previousElementIdList') ?? [];

        return is_array($previousElementIdList) && count($previousElementIdList) > 1;
    }

    private function checkElementsBelongSingleFlowRecursive(
        $divergentElementId,
        $forkElementId,
        $currentElementId,
        bool &$result,
        &$metElementIdList = null
    ): void {

        if ($divergentElementId === $currentElementId) {
            return;
        }

        if ($forkElementId === $currentElementId) {
            $result = true;

            return;
        }

        if (!$metElementIdList) {
            $metElementIdList = [];
        }

        $flowchartElementsDataHash = $this->getProcess()->get('flowchartElementsDataHash');

        $elementData = $flowchartElementsDataHash->$currentElementId;

        if (!isset($elementData->previousElementIdList)) {
            return;
        }

        foreach ($elementData->previousElementIdList as $elementId) {
            if (in_array($elementId, $metElementIdList)) {
                continue;
            }

            $this->checkElementsBelongSingleFlowRecursive(
                $divergentElementId,
                $forkElementId,
                $elementId,
                $result,
                $metElementIdList
            );
        }
    }

    protected function checkElementsBelongSingleFlow(
        $divergentElementId,
        $forkElementId,
        $elementId
    ): bool {

        $result = false;

        $this->checkElementsBelongSingleFlowRecursive($divergentElementId, $forkElementId, $elementId, $result);

        return $result;
    }
}
