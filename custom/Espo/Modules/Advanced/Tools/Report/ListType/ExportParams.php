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

class ExportParams
{
    /** @var ?string[] */
    private ?array $attributeList;
    /** @var ?string[] */
    private ?array $fieldList;
    private ?string $format;
    /** @var ?string[] */
    private ?array $ids;
    /** @var ?array<string, mixed> */
    private ?array $params;

    /**
     * @param ?string[] $attributeList
     * @param ?string[] $fieldList
     * @param ?string[] $ids
     * @params ?array<string, mixed> $params
     */
    public function __construct(
        ?array $attributeList,
        ?array $fieldList,
        ?string $format,
        ?array $ids,
        ?array $params
    ) {
        $this->attributeList = $attributeList;
        $this->fieldList = $fieldList;
        $this->format = $format;
        $this->ids = $ids;
        $this->params = $params;
    }

    /**
     * @return ?string[]
     */
    public function getAttributeList(): ?array
    {
        return $this->attributeList;
    }

    /**
     * @return ?string[]
     */
    public function getFieldList(): ?array
    {
        return $this->fieldList;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * @return ?string[]
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    /**
     * @return ?array<string, mixed>
     */
    public function getParams(): ?array
    {
        return $this->params;
    }
}