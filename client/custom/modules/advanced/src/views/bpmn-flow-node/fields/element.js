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

define('advanced:views/bpmn-flow-node/fields/element', ['views/fields/varchar'], function (Dep) {

    return Dep.extend({

        listTemplate: 'advanced:bpmn-flow-node/fields/element/detail',

        getValueForDisplay: function () {
            let stringValue = this.translate(this.model.get('elementType'), 'elements', 'BpmnFlowchart');

            let elementData = this.model.get('elementData') || {};
            let data = this.model.get('data') || {};

            let text = elementData.text;

            if (text) {
                stringValue += ' · ' + this.getHelper().escapeString(text);
            }

            let elementType = this.model.get('elementType') ;

            if (elementType === 'taskUser' && this.model.get('userTaskId')) {
                stringValue = '<a href="#BpmnUserTask/view/'+this.model.get('userTaskId')+'">' +
                    this.getHelper().escapeString(stringValue) +
                    '</a>';
            }

            if (
                elementType === 'callActivity' ||
                elementType === 'subProcess' ||
                elementType === 'eventSubProcess'
            ) {
                if (
                    (
                        elementData.callableType === 'Process' ||
                        elementType === 'subProcess' ||
                        elementType === 'eventSubProcess'
                    ) &&
                    data.subProcessId
                ) {
                    stringValue = '<a href="#BpmnProcess/view/' + data.subProcessId + '">' +
                        this.getHelper().escapeString(stringValue) +
                        '</a>';
                }

                if (data.errorTriggered) {
                    let errorPart = this.translate('Error', 'labels', 'BpmnFlowchart');

                    if (data.errorCode) {
                        errorPart += ' ' + data.errorCode;
                    }

                    stringValue += ' · <span class="text-danger">' +
                        this.getHelper().escapeString(errorPart) +
                        '</span>';
                }
            }

            if (
                elementType === 'eventIntermediateConditionalBoundary' ||
                elementType === 'eventStartConditionalEventSubProcess'
            ) {
                if (data.isOpposite) {
                    stringValue = this.translate('Reset', 'labels', 'BpmnFlowNode') + ': ' + stringValue;
                }
            }

            text = Handlebars.Utils.escapeExpression(this.stripTags(stringValue));

            stringValue = '<span title="'+text+'">' + stringValue + '</span>';

            return stringValue;
        },

        stripTags: function (text) {
            text = text || '';

            if (typeof text === 'string' || text instanceof String) {
                return text.replace(/<\/?[^>]+(>|$)/g, '');
            }

            return text;
        },
    });
});
