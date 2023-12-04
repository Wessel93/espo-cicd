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

define('advanced:views/workflow/condition-fields/subject-type-date', 'view', function (Dep) {

    return Dep.extend({

        template: 'advanced:workflow/condition-fields/subject-type',

        list: ['today', 'field'],

        data: function () {
            return {
                value: this.options.value,
                list: this.list,
                readOnly: this.options.readOnly,
            };
        },

    });
});
