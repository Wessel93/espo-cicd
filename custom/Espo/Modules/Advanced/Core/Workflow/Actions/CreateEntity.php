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

class CreateEntity extends BaseEntity
{
    protected function run(Entity $entity, stdClass $actionData): bool
    {
        $linkEntityName = $actionData->link;

        $GLOBALS['log']
            ->debug('Workflow\Actions\\'.$actionData->type.': Start creating a new entity ['.$linkEntityName.'].');

        $newEntity = $this->entityManager->getEntity($linkEntityName);

        $data = $this->getDataToFill($newEntity, $actionData->fields);
        $newEntity->set($data);

        if (!empty($actionData->formula)) {
            $this->getFormulaManager()->run($actionData->formula, $newEntity, $this->getFormulaVariables());
        }

        if (isset($actionData->linkList) && count($actionData->linkList)) {
            foreach ($actionData->linkList as $link) {
                if ($newEntity->getRelationType($link) === Entity::BELONGS_TO) {
                    $newEntity->set($link . 'Id', $entity->getId());
                }
                else if ($newEntity->getRelationType($link) === Entity::BELONGS_TO_PARENT) {
                    $newEntity->set($link . 'Id', $entity->getId());
                    $newEntity->set($link . 'Type', $entity->getEntityType());
                }
            }
        }

        $this->entityManager->saveEntity($newEntity, [
            'workflowId' => $this->getWorkflowId(),
            'createdById' => $newEntity->get('createdById') ?? 'system',
        ]);

        if (isset($actionData->linkList) && count($actionData->linkList)) {
            foreach ($actionData->linkList as $link) {
                if (
                    !in_array(
                        $newEntity->getRelationType($link),
                        [$newEntity::BELONGS_TO, Entity::BELONGS_TO_PARENT]
                    )
                ) {
                    $this->entityManager
                        ->getRDBRepository($newEntity->getEntityType())
                        ->getRelation($newEntity, $link)
                        ->relate($entity);
                }
            }
        }

        if ($this->createdEntitiesData && !empty($actionData->elementId) && !empty($actionData->id)) {
            $this->createdEntitiesDataIsChanged = true;

            $alias = $actionData->elementId . '_' . $actionData->id;

            $this->createdEntitiesData->$alias = (object) [
                'entityType' => $newEntity->getEntityType(),
                'entityId' => $newEntity->getId(),
            ];
        }

        $GLOBALS['log']
            ->debug('Workflow\Actions\\'. $actionData->type. ': ' .
                ' End creating a new entity ['.$linkEntityName.', ' . $newEntity->getId() . '].');

        return true;
    }
}
