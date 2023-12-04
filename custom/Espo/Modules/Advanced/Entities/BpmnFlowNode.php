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

namespace Espo\Modules\Advanced\Entities;

use Espo\Core\ORM\Entity;

class BpmnFlowNode extends Entity
{
    public const ENTITY_TYPE = 'BpmnFlowNode';

    public const STATUS_CREATED = 'Created';
    public const STATUS_IN_PROCESS = 'In Process';
    public const STATUS_PENDING = 'Pending';
    public const STATUS_STANDBY = 'Standby';
    public const STATUS_FAILED = 'Failed';
    public const STATUS_PROCESSED = 'Processed';
    public const STATUS_REJECTED = 'Rejected';
    public const STATUS_INTERRUPTED = 'Interrupted';

    public function getStatus(): ?string
    {
        return $this->get('status');
    }

    public function getProcessId(): ?string
    {
        return $this->get('processId');
    }

    public function getTargetId(): ?string
    {
        return $this->get('targetId');
    }

    public function getTargetType(): ?string
    {
        return $this->get('targetType');
    }

    public function getElementType(): ?string
    {
        return $this->get('elementType');
    }

    public function getElementId(): ?string
    {
        return $this->get('elementId');
    }

    public function getFlowchartId(): ?string
    {
        return $this->get('flowchartId');
    }

    /**
     * @return mixed
     */
    public function getElementDataItemValue(string $name)
    {
        $data = $this->get('elementData');

        if (!$data) {
            $data = (object) [];
        }

        if (!property_exists($data, $name)) {
            return null;
        }

        return $data->$name;
    }

    /**
     * @return mixed
     */
    public function getDataItemValue(string $name)
    {
        $data = $this->get('data');

        if (!$data) {
            $data = (object) [];
        }

        if (!property_exists($data, $name)) {
            return null;
        }

        return $data->$name;
    }

    public function setDataItemValue(string $name, $value): void
    {
        $data = $this->get('data');

        if (!$data) {
            $data = (object) [];
        }

        $data->$name = $value;

        $this->set('data', $data);
    }
}
