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

use Espo\ORM\Collection;
use stdClass;

class Result
{
    private Collection $collection;
    private int $total;
    /** @var ?string[] */
    private ?array $columns;
    private ?stdClass $columnsData;

    /**
     * @param ?string[] $columns
     */
    public function __construct(
        Collection $collection,
        int $total,
        ?array $columns = null,
        ?stdClass $columnsData = null
    ) {
        $this->collection = $collection;
        $this->total = $total;
        $this->columns = $columns;
        $this->columnsData = $columnsData;
    }

    public function getCollection(): Collection
    {
        return $this->collection;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return string[]
     */
    public function getColumns(): ?array
    {
        return $this->columns;
    }

    public function getColumnsData(): ?stdClass
    {
        return $this->columnsData;
    }
}