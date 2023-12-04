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

namespace Espo\Modules\Advanced\Tools\Report\ListType;

class SubReportParams
{
    private int $groupIndex;
    /** @var ?scalar */
    private $groupValue;
    private bool $hasGroupValue2;
    /** @var ?scalar */
    private $groupValue2;

    public function __construct(
        int $groupIndex,
        $groupValue,
        bool $hasGroupValue2 = false,
        $groupValue2 = null
    ) {
        $this->groupIndex = $groupIndex;
        $this->groupValue = $groupValue;
        $this->groupValue2 = $groupValue2;
        $this->hasGroupValue2 = $hasGroupValue2;
    }

    public function getGroupIndex(): int
    {
        return $this->groupIndex;
    }

    /**
     * @return ?scalar
     */
    public function getGroupValue()
    {
        return $this->groupValue;
    }

    public function hasGroupValue2(): bool
    {
        return $this->hasGroupValue2;
    }

    /**
     * @return ?scalar
     */
    public function getGroupValue2()
    {
        return $this->groupValue2;
    }
}
