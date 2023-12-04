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

namespace Espo\Modules\Advanced\Core\Cleanup;

use Espo\ORM\EntityManager;
use Espo\Entities\Extension;

use Espo\Core\{
    Cleanup\Cleanup,
    InjectableFactory,
    Utils\File\ZipArchive,
    Job\Job\Data as JobData,
    Utils\File\Manager as FileManager,
};

use Exception;

class Integrity implements Cleanup
{
    private $file;

    private $statusFieldName;

    private string $name64 = 'QWR2YW5jZWQgUGFjaw==';

    private string $class64 = 'RXNwb1xNb2R1bGVzXEFkdmFuY2VkXENvcmVcQXBwXEpvYlJ1bm5lcg==';

    private string $file64 = 'Y3VzdG9tL0VzcG8vTW9kdWxlcy9BZHZhbmNlZC9Db3JlL0FwcC9Kb2JSdW5uZXIucGhw';

    private string $hash = '60e5a7210291f4c8d69fe6346a216cf9';

    private string $packagePath = 'data/upload/extensions';

    private FileManager $fileManager;

    private EntityManager $entityManager;

    private InjectableFactory $injectableFactory;

    public function __construct(
        FileManager $fileManager,
        EntityManager $entityManager,
        InjectableFactory $injectableFactory
    ) {
        $this->fileManager = $fileManager;
        $this->entityManager = $entityManager;
        $this->injectableFactory = $injectableFactory;

        $this->file = base64_decode($this->file64);
        $this->statusFieldName = base64_decode('bGljZW5zZVN0YXR1cw==');
    }

    public function process(): void
    {
        $this->check();
        $this->checkRun();
    }

    private function getExtension(): ?Extension
    {
        return $this->entityManager
            ->getRepository('Extension')
            ->where([
                'name' => base64_decode($this->name64),
            ])
            ->order('createdAt', true)
            ->findOne();
    }

    private function check(): void
    {
        if (!file_exists($this->file)) {
            $this->restore();

            return;
        }

        if ($this->hash !== hash_file('md5', $this->file)) {
            $this->restore();
        }
    }

    private function restore(): void
    {
        $current = $this->getExtension();

        if (!$current) {

            return;
        }

        $path = $this->packagePath . '/' . $current->get('id');

        if (!file_exists($path . 'z')) {

            return;
        }

        $zip = new ZipArchive($this->fileManager);
        $zip->unzip($path . 'z', $path);

        $file = $path . '/files/' . $this->file;

        if (!file_exists($file)) {

            return;
        }

        try {
            $this->fileManager->copy($file, dirname($this->file), false, null, true);
        }
        catch (Exception $e) {}

        $this->fileManager->removeInDir($path, true);
    }

    private function checkRun(): void
    {
        $current = $this->getExtension();

        if (!$current) {

            return;
        }

        if (!$current->has($this->statusFieldName)) {

            return;
        }

        if ($current->get($this->statusFieldName)) {

            return;
        }

        $service = $this->injectableFactory->create(
            base64_decode($this->class64)
        );

        if (!$service) {

            return;
        }

        if (!method_exists($service, 'run')) {

            return;
        }

        $service->run(JobData::create());
    }
}
