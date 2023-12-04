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

namespace Espo\Modules\Advanced\Core\Workflow\Conditions;

use Espo\ORM\Entity;
use stdClass;

class NotEquals extends Equals
{
    /**
     * @param mixed $fieldValue
     */
    protected function compare($fieldValue): bool
    {
        return !(parent::compare($fieldValue));
    }

    protected function compareComplex(Entity $entity, stdClass $condition): bool
    {
        if (empty($condition->fieldValueMap)) {
            return false;
        }

        $fieldValueMap = $condition->fieldValueMap;

        $isEqual = true;

        foreach ($fieldValueMap as $field => $value) {
            if ($entity->get($field) !== $value) {
                $isEqual = false;

                break;
            }
        }

        return !$isEqual;
    }
}
