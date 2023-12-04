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

define('advanced:views/bpmn-user-task/record/resolve', ['views/record/base'], function (Dep) {

    return Dep.extend({

        template: 'advanced:bpmn-user-task/record/resolve',

        setup: function () {
            Dep.prototype.setup.call(this);
        },

        setupBeforeFinal: function () {
            this.dynamicLogicDefs = this.getMetadata().get('clientDefs.' + this.model.name + '.dynamicLogic') || {};
            Dep.prototype.setupBeforeFinal.call(this);

            this.createField('resolution', 'views/fields/enum', {}, 'edit');
            this.createField('resolutionNote', 'views/fields/text', {}, 'edit');

            this.once('after:render', () => {
                if (this.recordHelper.hasFieldOptionList('resolution')) {
                    this.getFieldView('resolution').setOptionList(this.recordHelper.getFieldOptionList('resolution'));
                }
            });
        }
    });
});
