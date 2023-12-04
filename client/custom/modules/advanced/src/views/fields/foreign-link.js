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

define('advanced:views/fields/foreign-link', ['views/fields/link'], function (Dep) {

    return Dep.extend({

        setup: function () {
            var a = this.name.split('_');
            var link = a[0];
            var field = a[1];

            var linkEntityType = this.getMetadata().get(['entityDefs', this.model.name, 'links', link, 'entity']);
            this.foreignScope = this.getMetadata().get(['entityDefs', linkEntityType, 'links', field, 'entity']);

            Dep.prototype.setup.call(this)
        }
    });
});
