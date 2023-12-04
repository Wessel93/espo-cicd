<?php
return [
  'useCache' => true,
  'jobMaxPortion' => 15,
  'jobRunInParallel' => true,
  'jobPoolConcurrencyNumber' => 8,
  'daemonMaxProcessNumber' => 5,
  'daemonInterval' => 10,
  'daemonProcessTimeout' => 36000,
  'recordsPerPage' => 20,
  'recordsPerPageSmall' => 5,
  'recordsPerPageSelect' => 10,
  'recordsPerPageKanban' => 5,
  'applicationName' => 'EspoCRM',
  'version' => '8.0.2',
  'timeZone' => 'UTC',
  'dateFormat' => 'DD.MM.YYYY',
  'timeFormat' => 'HH:mm',
  'weekStart' => 0,
  'thousandSeparator' => ',',
  'decimalMark' => '.',
  'exportDelimiter' => ',',
  'currencyList' => [
    0 => 'USD',
    1 => 'EUR'
  ],
  'defaultCurrency' => 'USD',
  'baseCurrency' => 'USD',
  'currencyRates' => [
    'EUR' => 1
  ],
  'currencyNoJoinMode' => false,
  'outboundEmailIsShared' => true,
  'outboundEmailFromName' => 'Shelter blueprint',
  'outboundEmailFromAddress' => 'cash-im@redcross.nl',
  'smtpServer' => 'smtp.sendgrid.net',
  'smtpPort' => 587,
  'smtpAuth' => true,
  'smtpSecurity' => 'TLS',
  'smtpUsername' => 'apikey',
  'language' => 'en_GB',
  'authenticationMethod' => 'Espo',
  'globalSearchEntityList' => [
    0 => 'Account',
    1 => 'Contact',
    2 => 'Lead',
    3 => 'Opportunity'
  ],
  'tabList' => [
    0 => 'AffectedHousehold',
    1 => 'Accommodation',
    2 => 'Combined',
    3 => '_delimiter_',
    4 => 'CashDistribution',
    5 => 'Transfer',
    6 => 'MessageTemplate',
    7 => 'Message',
    8 => 'Report',
    9 => 'Bank',
    10 => 'Monitoring'
  ],
  'quickCreateList' => [
    0 => 'Account',
    1 => 'Lead',
    2 => 'Contact',
    3 => 'Meeting',
    4 => 'Opportunity',
    5 => 'Call',
    6 => 'Task',
    7 => 'Case',
    8 => 'Email',
    9 => 'AffectedHousehold',
    10 => 'AdequacyCriteria',
    11 => 'Accommodation'
  ],
  'exportDisabled' => false,
  'adminNotifications' => true,
  'adminNotificationsNewVersion' => true,
  'adminNotificationsCronIsNotConfigured' => true,
  'adminNotificationsNewExtensionVersion' => true,
  'assignmentEmailNotifications' => false,
  'assignmentEmailNotificationsEntityList' => [
    0 => 'Lead',
    1 => 'Opportunity',
    2 => 'Task',
    3 => 'Case'
  ],
  'assignmentNotificationsEntityList' => [
    0 => 'Call',
    1 => 'Email',
    2 => 'BpmnUserTask'
  ],
  'portalStreamEmailNotifications' => true,
  'streamEmailNotificationsEntityList' => [
    0 => 'Case'
  ],
  'streamEmailNotificationsTypeList' => [
    0 => 'Post',
    1 => 'Status',
    2 => 'EmailReceived'
  ],
  'emailNotificationsDelay' => 30,
  'emailMessageMaxSize' => 10,
  'emailRecipientAddressMaxCount' => 100,
  'notificationsCheckInterval' => 10,
  'popupNotificationsCheckInterval' => 15,
  'maxEmailAccountCount' => 2,
  'followCreatedEntities' => false,
  'b2cMode' => false,
  'theme' => 'Hazyblue',
  'themeParams' => (object) [
    'navbar' => 'top'
  ],
  'massEmailMaxPerHourCount' => 100,
  'massEmailVerp' => false,
  'personalEmailMaxPortionSize' => 50,
  'inboundEmailMaxPortionSize' => 50,
  'emailAddressLookupEntityTypeList' => [
    0 => 'User',
    1 => 'Contact',
    2 => 'Lead',
    3 => 'Account'
  ],
  'emailAddressEntityLookupDefaultOrder' => [
    0 => 'User',
    1 => 'Contact',
    2 => 'Lead',
    3 => 'Account'
  ],
  'phoneNumberEntityLookupDefaultOrder' => [
    0 => 'User',
    1 => 'Contact',
    2 => 'Lead',
    3 => 'Account'
  ],
  'authTokenLifetime' => 0,
  'authTokenMaxIdleTime' => 48,
  'userNameRegularExpression' => '[^a-z0-9\\-@_\\.\\s]',
  'addressFormat' => 1,
  'displayListViewRecordCount' => true,
  'dashboardLayout' => [
    0 => (object) [
      'name' => 'My Espo',
      'layout' => [
        0 => (object) [
          'id' => 'default-stream',
          'name' => 'Stream',
          'x' => 0,
          'y' => 0,
          'width' => 2,
          'height' => 4
        ],
        1 => (object) [
          'id' => 'default-activities',
          'name' => 'Activities',
          'x' => 2,
          'y' => 2,
          'width' => 2,
          'height' => 4
        ]
      ]
    ]
  ],
  'calendarEntityList' => [
    0 => 'Meeting',
    1 => 'Call',
    2 => 'Task'
  ],
  'activitiesEntityList' => [
    0 => 'Meeting',
    1 => 'Call'
  ],
  'historyEntityList' => [
    0 => 'Meeting',
    1 => 'Call',
    2 => 'Email'
  ],
  'busyRangesEntityList' => [
    0 => 'Meeting',
    1 => 'Call'
  ],
  'emailAutoReplySuppressPeriod' => '2 hours',
  'emailAutoReplyLimit' => 5,
  'cleanupJobPeriod' => '1 month',
  'cleanupActionHistoryPeriod' => '15 days',
  'cleanupAuthTokenPeriod' => '1 month',
  'cleanupSubscribers' => true,
  'currencyFormat' => 2,
  'currencyDecimalPlaces' => 2,
  'aclAllowDeleteCreated' => false,
  'aclAllowDeleteCreatedThresholdPeriod' => '24 hours',
  'attachmentAvailableStorageList' => NULL,
  'attachmentUploadMaxSize' => 256,
  'attachmentUploadChunkSize' => 4,
  'inlineAttachmentUploadMaxSize' => 20,
  'textFilterUseContainsForVarchar' => false,
  'tabColorsDisabled' => false,
  'massPrintPdfMaxCount' => 50,
  'emailKeepParentTeamsEntityList' => [
    0 => 'Case'
  ],
  'streamEmailWithContentEntityTypeList' => [
    0 => 'Case'
  ],
  'recordListMaxSizeLimit' => 200,
  'noteDeleteThresholdPeriod' => '1 month',
  'noteEditThresholdPeriod' => '7 days',
  'emailForceUseExternalClient' => false,
  'useWebSocket' => true,
  'auth2FAMethodList' => [
    0 => 'Totp'
  ],
  'auth2FAInPortal' => false,
  'personNameFormat' => 'firstLast',
  'newNotificationCountInTitle' => false,
  'pdfEngine' => 'Dompdf',
  'smsProvider' => NULL,
  'defaultFileStorage' => 'EspoUploadDir',
  'ldapUserNameAttribute' => 'sAMAccountName',
  'ldapUserFirstNameAttribute' => 'givenName',
  'ldapUserLastNameAttribute' => 'sn',
  'ldapUserTitleAttribute' => 'title',
  'ldapUserEmailAddressAttribute' => 'mail',
  'ldapUserPhoneNumberAttribute' => 'telephoneNumber',
  'ldapUserObjectClass' => 'person',
  'ldapPortalUserLdapAuth' => false,
  'passwordGenerateLength' => 10,
  'massActionIdleCountThreshold' => 100,
  'exportIdleCountThreshold' => 1000,
  'oidcJwtSignatureAlgorithmList' => [
    0 => 'RS256'
  ],
  'oidcUsernameClaim' => 'sub',
  'oidcFallback' => true,
  'oidcScopes' => [
    0 => 'profile',
    1 => 'email',
    2 => 'phone'
  ],
  'cacheTimestamp' => 1701695405,
  'microtime' => 1701695405.659488,
  'siteUrl' => 'http://4.210.240.33',
  'fullTextSearchMinLength' => 4,
  'appTimestamp' => 1701695405,
  'latestVersion' => '8.0.5',
  'userThemesDisabled' => false,
  'avatarsDisabled' => false,
  'scopeColorsDisabled' => false,
  'tabIconsDisabled' => false,
  'dashletsOptions' => (object) [],
  'companyLogoId' => '6537ac807398e5fbf',
  'companyLogoName' => 'IFRC_Logo_original.png',
  'maintenanceMode' => false,
  'cronDisabled' => false,
  'adminPanelIframeUrl' => 'https://s.espocrm.com/?advanced-pack=8574d8ccb6560d675a7f3219930bc620',
  'latestExtensionVersions' => [
    'Advanced Pack' => '3.0.13'
  ],
  'outboundEmailBccAddress' => NULL,
  'massEmailDisableMandatoryOptOutLink' => false,
  'massEmailOpenTracking' => false,
  'emailAddressIsOptedOutByDefault' => false,
  'cleanupDeletedRecords' => false,
  'fiscalYearShift' => 0,
  'addressCountryList' => [],
  'addressCityList' => [],
  'addressStateList' => [],
  'workingTimeCalendarName' => NULL,
  'workingTimeCalendarId' => NULL
];
