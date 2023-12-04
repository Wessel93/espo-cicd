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

define('advanced:views/workflow/conditions/array', ['advanced:views/workflow/conditions/base'], function (Dep) {

    return Dep.extend({

        template: 'advanced:workflow/conditions/enum',

        defaultConditionData: {
            comparison: 'notEmpty',
            subjectType: 'value',
        },

        comparisonList: [
            'notEmpty',
            'isEmpty',
            'has',
            'notHas',
            'changed',
            'notChanged',
        ],

        data: function () {
            return _.extend({
            }, Dep.prototype.data.call(this));
        },

        getSubjectInputViewName: function (subjectType) {
            var optionList = this.getMetadata()
                .get(['entityDefs', this.options.actualEntityType, 'fields', this.options.actualField, 'options']) ||
                [];

            if (optionList.length) {
                return 'advanced:views/workflow/condition-fields/subjects/enum-input';
            } else {
                return 'advanced:views/workflow/condition-fields/subjects/text-input';
            }
        },
    });
});
