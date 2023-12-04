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

define('advanced:views/bpmn-flow-node/modals/view-error', ['views/modal', 'model'], function (Dep, Model) {
    /**
     * @module module:advanced_views/bpmn-flow-node/modals/view-error
     */

    /**
     * @class
     * @name Class
     * @memberOf module:advanced_views/bpmn-flow-node/modals/view-error
     * @extends module:views/modal.Class
     */
    return Dep.extend(/** @lends module:advanced_views/bpmn-flow-node/modals/view-error.Class# */{

        templateContent: `<div class="record no-side-margin">{{{record}}}</div>`,

        className: 'dialog dialog-record',
        backdrop: true,

        setup: function () {
            this.headerText = this.translate('View Error', 'labels', 'BpmnProcess');

            /** @type {module:model.Class} */
            let model = new Model();
            model.name = 'Dummy';

            model.set({
                code: this.options.nodeData.code || null,
                message: this.options.nodeData.message || null,
            })

            this.createView('record', 'views/record/detail', {
                readOnly: true,
                bottomView: null,
                sideView: null,
                buttonsDisabled: true,
                scope: 'Dummy',
                model: model,
                el: this.getSelector() + ' .record',
                detailLayout: [
                    {
                        rows: [
                            [
                                {
                                    name: 'code',
                                    view: 'views/fields/varchar',
                                    customLabel: this.translate('errorCode', 'fields', 'BpmnFlowchartElement'),
                                },
                                false
                            ],
                            [
                                {
                                    name: 'message',
                                    view: 'views/fields/varchar',
                                    customLabel: this.translate('Error Message', 'labels', 'BpmnFlowchartElement'),
                                }
                            ]
                        ]
                    }
                ],
            });
        },
    });
});
