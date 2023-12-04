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

define('advanced:views/workflow/fields/process-start-element-id', 'views/fields/enum', function (Dep) {

    return Dep.extend({

        setup: function () {
            Dep.prototype.setup.call(this);

            this.listenTo(this.model, 'change:startElementNames', function (model, startElementNames) {
                this.translatedOptions = startElementNames;
            });

            this.listenTo(this.model, 'change:startElementIdList', function (model, startElementIdList) {
                this.params.options = startElementIdList || [];
                this.reRender();
            });

            this.listenTo(this.model, 'change:target', function (model, startElementIdList) {
                this.params.options = [];
                this.reRender();
            });
        },

    });
});
