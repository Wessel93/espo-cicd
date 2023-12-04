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

define('advanced:views/bpmn-flow-node/record/row-actions/default', ['views/record/row-actions/default'], function (Dep) {

    return Dep.extend({

        getActionList: function () {
            let list = [];

            let status = this.model.get('status');
            let elementType = this.model.get('elementType');

            if (['In Process'].includes(status)) {
                list.push({
                    action: 'interruptFlowNode',
                    html: this.translate('Interrupt', 'labels', 'BpmnProcess'),
                    data: {
                        id: this.model.id,
                    },
                });
            }

            if (!['Processed', 'Interrupted', 'Rejected', 'Failed'].includes(status)) {
                list.push({
                    action: 'rejectFlowNode',
                    html: this.translate('Reject', 'labels', 'BpmnProcess'),
                    data: {
                        id: this.model.id,
                    },
                });
            }

            if (
                ['eventStartError', 'eventIntermediateErrorBoundary'].includes(elementType) &&
                status === 'Processed'
            ) {
                list.push({
                    action: 'viewError',
                    html: this.translate('View Error', 'labels', 'BpmnProcess'),
                    data: {
                        id: this.model.id,
                    },
                });
            }

            return list;
        },
    });
});
