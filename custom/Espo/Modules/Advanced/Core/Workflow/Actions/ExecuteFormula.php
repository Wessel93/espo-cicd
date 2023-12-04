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

namespace Espo\Modules\Advanced\Core\Workflow\Actions;

use Espo\ORM\Entity;
use stdClass;

class ExecuteFormula extends BaseEntity
{
    protected function run(Entity $entity, stdClass $actionData): bool
    {
        if (empty($actionData->formula)) {
            return true;
        }

        $reloadedEntity = $this->getEntityManager()->getEntity($entity->getEntityType(), $entity->get('id'));

        $variables = $this->getFormulaVariables();

        $this->getFormulaManager()->run($actionData->formula, $reloadedEntity, $variables);

        $this->updateVariables($variables);

        $isChanged = false;

        $changedMap = (object) [];

        foreach ($reloadedEntity->getAttributeList() as $attribute) {
            if ($reloadedEntity->isAttributeChanged($attribute)) {
                $changedMap->$attribute = $reloadedEntity->get($attribute);

                $isChanged = true;
            }
        }

        if ($isChanged) {
            $this->getEntityManager()->saveEntity($reloadedEntity, [
                'modifiedById' => 'system',
                'skipWorkflow' => !$this->bpmnProcess,
                'workflowId' => $this->getWorkflowId(),
            ]);

            $entity->set($changedMap);
        }

        return true;
    }
}
