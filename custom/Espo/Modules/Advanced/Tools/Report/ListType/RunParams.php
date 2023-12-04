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

class RunParams
{
    private bool $skipRuntimeFiltersCheck = false;
    private bool $returnSthCollection = false;
    private bool $isExport = false;
    private bool $fullSelect = false;
    /** @var ?string[] */
    private ?array $customColumnList = null;

    private function __construct() {}

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

    public function withReturnSthCollection(bool $value = true): self
    {
        $obj = clone $this;
        $obj->returnSthCollection = $value;

        return $obj;
    }

    public function withIsExport(bool $value = true): self
    {
        $obj = clone $this;
        $obj->isExport = $value;

        return $obj;
    }

    public function withFullSelect(bool $value = true): self
    {
        $obj = clone $this;
        $obj->fullSelect = $value;

        return $obj;
    }

    /**
     * @param ?string[] $value
     */
    public function withCustomColumnList(?array $value): self
    {
        $obj = clone $this;
        $obj->customColumnList = $value;

        return $obj;
    }

    public function returnSthCollection(): bool
    {
        return $this->returnSthCollection;
    }

    public function isExport(): bool
    {
        return $this->isExport;
    }

    public function isFullSelect(): bool
    {
        return $this->fullSelect;
    }

    /**
     * @return ?string[]
     */
    public function getCustomColumnList(): ?array
    {
        return $this->customColumnList;
    }
}
