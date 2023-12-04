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

use Espo\Core\Select\Where\Item as WhereItem;

use stdClass;

class Data
{
    private string $entityType;
    /** @var string[] */
    private array $columns;
    /** @var ?string */
    private ?string $orderBy;
    private ?stdClass $columnsData;
    private ?WhereItem $filtersWhere;

    /**
     * @param string[] $columns
     */
    public function __construct(
        string $entityType,
        array $columns,
        ?string $orderBy,
        ?stdClass $columnsData,
        ?WhereItem $filtersWhere
    ) {
        $this->entityType = $entityType;
        $this->columns = $columns;
        $this->orderBy = $orderBy;
        $this->columnsData = $columnsData;
        $this->filtersWhere = $filtersWhere;
    }

    public function getEntityType(): string
    {
        return $this->entityType;
    }

    /**
     * @return string[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function getColumnsData(): ?stdClass
    {
        return $this->columnsData;
    }

    /**
     * @param string[] $columns
     */
    public function withColumns(array $columns): self
    {
        $obj = clone $this;
        $obj->columns = $columns;

        return $obj;
    }

    public function getFiltersWhere(): ?WhereItem
    {
        return $this->filtersWhere;
    }
}
