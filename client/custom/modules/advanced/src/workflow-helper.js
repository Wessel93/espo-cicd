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

define('advanced:workflow-helper', ['view'], function (Fake) {

    var WorkflowHelper = function (metadata) {
        this.metadata = metadata;
    };

    _.extend(WorkflowHelper.prototype, {

        getComplexFieldEntityType: function (field, entityType) {
            if (~field.indexOf('.') && !~field.indexOf('created:')) {
                var arr = field.split('.');
                var link = arr[0];

                return this.getMetadata().get(['entityDefs', entityType, 'links', link, 'entity']);
            }

            return entityType;
        },

        getComplexFieldLinkPart: function (field) {
            if (~field.indexOf('.')) {
                var arr = field.split('.');

                return arr[0];
            }

            return null;
        },

        getComplexFieldFieldPart: function (field) {
            if (~field.indexOf('.')) {
                var arr = field.split('.');

                return arr[1];
            }

            return field;
        },

        getComplexFieldForeignEntityType: function (field, entityType) {
            var targetLinkEntityType;

            if (~field.indexOf('.')) {
                var arr = field.split('.');
                var foreignField = arr[1];
                var link = arr[0];

                var foreignEntityType = this.getMetadata()
                    .get(['entityDefs', entityType, 'links', link, 'entity']);

                targetLinkEntityType = this.getMetadata()
                    .get(['entityDefs', foreignEntityType, 'links', foreignField, 'entity']);
            } else {
                targetLinkEntityType = this.getMetadata().get(['entityDefs', entityType, 'links', field, 'entity']);
            }

            return targetLinkEntityType;
        },


        getMetadata: function () {
            return this.metadata;
        },
    });

    return WorkflowHelper;
});
