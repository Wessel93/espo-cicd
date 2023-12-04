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

define('advanced:views/bpmn-process/modals/view-variables', ['views/modal'], function (Dep) {

    return Dep.extend({

        templateContent: `<div class="record no-side-margin">{{{record}}}</div>`,

        className: 'dialog dialog-record',
        backdrop: true,

        setup: function () {
            this.headerText = this.translate('variables', 'fields', 'BpmnProcess');

            this.createView('record', 'views/record/detail', {
                readOnly: true,
                isWide: true,
                bottomView: null,
                sideView: null,
                buttonsDisabled: true,
                scope: this.model.entityType,
                model: this.model,
                el: this.getSelector() + ' .record',
                detailLayout: [
                    {
                        rows: [
                            [
                                {
                                    name: 'variables',
                                    noLabel: true,
                                }
                            ]
                        ]
                    }
                ],
            });
        },
    });
});
