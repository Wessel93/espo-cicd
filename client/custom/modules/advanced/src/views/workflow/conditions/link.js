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

define('advanced:views/workflow/conditions/link', ['advanced:views/workflow/conditions/base'], function (Dep) {

    return Dep.extend({

        template: 'advanced:workflow/conditions/base',

        defaultConditionData: {
            comparison: 'notEmpty',
        },

        comparisonList: [
            'notEmpty',
            'isEmpty',
            'equals',
            'notEquals',
            'changed',
            'notChanged',
        ],

        setupComparisonList: function () {
            Dep.prototype.setupComparisonList.call(this)

            if (this.fieldType === 'image' || this.fieldType === 'file') {
                var comparisonList = [];

                Espo.Utils.clone(this.comparisonList).forEach(item => {
                    if (~['equals', 'notEquals', 'wasEqual', 'wasNotEqual'].indexOf(item)) {
                        return;
                    }

                    comparisonList.push(item);
                });

                this.comparisonList = comparisonList;
            }
        },

        data: function () {
            return _.extend({
            }, Dep.prototype.data.call(this));
        },

        getSubjectInputViewName: function (subjectType) {
            return 'advanced:views/workflow/condition-fields/subjects/link';
        },
    });
});
