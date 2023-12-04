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

namespace Espo\Modules\Advanced\Tools\Report\GridType;

class RunParams
{
    private bool $skipRuntimeFiltersCheck;

    public function __construct(
        bool $skipRuntimeFiltersCheck = false
    ) {
        $this->skipRuntimeFiltersCheck = $skipRuntimeFiltersCheck;
    }

    public function skipRuntimeFiltersCheck(): bool
    {
        return $this->skipRuntimeFiltersCheck;
    }

    public function withSkipRuntimeFiltersCheck(bool $value = true): self
    {
        $obj = clone $this;
        $obj->skipRuntimeFiltersCheck = $value;

        return $obj;
    }

    public static function create(): self
    {
        return new self();
    }
}
