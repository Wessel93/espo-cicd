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

namespace Espo\Modules\Advanced\Core\ORM;

use Espo\ORM\Collection;
use Espo\ORM\EntityManager;

use PDOStatement;
use PDO;
use IteratorAggregate;

class SthCollection implements IteratorAggregate, Collection
{
    /** @var PDOStatement */
    private $sth;
    /** @var string */
    private $entityType;
    private $fieldDefs;
    private $linkMultipleFieldList;
    private $foreignLinkFieldDataList;
    /** @var CustomEntityFactory */
    private $customEntityFactory;
    /** @var EntityManager */
    private $entityManager;

    public function __construct(
        PDOStatement $sth,
        string $entityType,
        EntityManager $entityManager,
        $fieldDefs,
        $linkMultipleFieldList,
        $foreignLinkFieldDataList,
        CustomEntityFactory $customEntityFactory
    ) {
        $this->sth = $sth;
        $this->entityType = $entityType;
        $this->entityManager = $entityManager;
        $this->fieldDefs = $fieldDefs;
        $this->linkMultipleFieldList = $linkMultipleFieldList;
        $this->foreignLinkFieldDataList = $foreignLinkFieldDataList;
        $this->customEntityFactory = $customEntityFactory;
    }

    public function getIterator()
    {
        return (function () {
            while ($row = $this->sth->fetch(PDO::FETCH_ASSOC)) {

                $rowData = [];

                foreach ($row as $attr => $value) {
                    $attribute = str_replace('.', '_', $attr);

                    $rowData[$attribute] = $value;
                }

                $entity = $this->customEntityFactory->create($this->entityType, $this->fieldDefs);

                $entity->set($rowData);
                $entity->setAsFetched();

                foreach ($this->linkMultipleFieldList as $field) {
                    $entity->loadLinkMultipleField($field);
                }

                foreach ($this->foreignLinkFieldDataList as $item) {
                    $foreignId = $entity->get($item->name . 'Id');

                    if ($foreignId) {
                        $foreignEntity = $this->entityManager
                            ->getRDBRepository($item->entityType)
                            ->where(['id' => $foreignId])
                            ->select(['name'])
                            ->findOne();

                        if ($foreignEntity) {
                            $entity->set($item->name . 'Name', $foreignEntity->get('name'));
                        }
                    }
                }

                yield $entity;
            }
        })();
    }

    public function getValueMapList(): array
    {
        $list = [];

        foreach ($this as $entity) {
            $list[] = $entity->getValueMap();
        }

        return $list;
    }
}
