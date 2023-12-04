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

define('advanced:views/record/list-for-report', ['views/record/list'], function (Dep) {

    return Dep.extend({

        forcedCheckAllResultMassActionList: ['export'],
        checkAllResultMassActionList: ['export'],

        export: function () {
            let data = {};

            let fieldList = null;

            if (this.options.listLayout) {
                fieldList = [];

                this.options.listLayout.forEach(item => {
                    fieldList.push(item.name);
                });
            }

            if (!this.allResultIsChecked) {
                data.ids = this.checkedList;
            }

            data.id = this.options.reportId;

            if ('runtimeWhere' in this.options) {
                data.where = this.options.runtimeWhere;
            }

            if ('groupValue' in this.options) {
                data.groupValue = this.options.groupValue;
            }

            if ('groupIndex' in this.options) {
                data.groupIndex = this.options.groupIndex;
            }

            if (this.options.groupValue2 !== undefined) {
                data.groupValue2 = this.options.groupValue2;
            }

            data.sortBy = this.collection.sortBy;
            data.asc = this.collection.asc;

            data.orderBy = this.collection.orderBy;
            data.order = this.collection.order;

            let url = 'Report/action/exportList';

            Dep.prototype.export.call(this, data, url, fieldList);
        },
    });
});
