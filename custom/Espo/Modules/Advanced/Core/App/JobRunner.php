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

namespace Espo\Modules\Advanced\Core\App;

use Espo\Core\{
    Job\Job,
    Job\Job\Data,
    Utils\Config,
    ORM\EntityManager,
};

use Espo\Entities\Extension;

class JobRunner implements Job
{
    private Config $config;

    private EntityManager $entityManager;

    public function __construct(Config $config, EntityManager $entityManager)
    {
        $this->config = $config;
        $this->entityManager = $entityManager;
    }

    private $name = 'Advanced Pack';

    public function run(Data $data): void
    {
        $current = $this->entityManager
            ->getRepository(Extension::ENTITY_TYPE)
            ->where([
                'name' => $this->name,
            ])
            ->order('createdAt', true)
            ->findOne();

        $responseData = $this->validate($this->getData($current));

        $status = $responseData['status'] ?? null;
        $statusMessage = $responseData['statusMessage'] ?? null;

        if (!$status) {

            return;
        }

        if (!$current->has('licenseStatus')) {

            return;
        }

        if (
            $current->get('licenseStatus') == $status &&
            $current->get('licenseStatusMessage') == $statusMessage
        ) {

            return;
        }

        $current->set([
            'licenseStatus' => $status,
            'licenseStatusMessage' => $statusMessage,
        ]);

        $this->entityManager->saveEntity($current, [
            'skipAll' => true,
        ]);
    }

    private function getData(?Extension $current): array
    {
        $first = $this->entityManager
            ->getRepository(Extension::ENTITY_TYPE)
            ->select(['createdAt'])
            ->where([
                'name' => $this->name,
            ])
            ->order('createdAt')
            ->findOne(['withDeleted' => true]);

        return [
            'id' => '8574d8ccb6560d675a7f3219930bc620',
            'name' => $this->name,
            'version' => $current ? $current->get('version') : null,
            'updatedAt' => $current ? $current->get('createdAt') : null,
            'installedAt' => $first ? $first->get('createdAt') : null,
            'site' => $this->config->get('siteUrl'),
            'espoVersion' => $this->config->get('version'),
            'applicationName' => $this->config->get('applicationName'),
        ];
    }

    private function validate(array $data): ?array
    {
        if (!function_exists('curl_version')) {
            return null;
        }

        $ch = curl_init();

        $payload = json_encode($data);
        curl_setopt($ch, CURLOPT_URL, base64_decode('aHR0cHM6Ly9zLmVzcG9jcm0uY29tL2xpY2Vuc2Uv'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return json_decode($result, true);
        }

        return null;
    }
}
