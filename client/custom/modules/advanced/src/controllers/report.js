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

define('advanced:controllers/report', ['controllers/record'], function (Dep) {

    return Dep.extend({

        create: function (options) {
            options = options || {};

            var hasAttributes = !!options.attributes;

            options.attributes = options.attributes || {};

            if ('type' in options) {
                options.attributes.type = options.type;
            } else if (!options.attributes.type) {
                options.attributes.type = 'Grid';
            }

            if ('entityType' in options) {
                options.attributes.entityType = options.entityType;
            } else {
                if (!hasAttributes && options.attributes.type !== 'JointGrid') {
                    throw new Espo.Exceptions.NotFound();
                }
            }

            if ('categoryId' in options) {
                options.attributes.categoryId = options.categoryId;
            }

            if ('categoryName' in options) {
                options.attributes.categoryName = options.categoryName;
            }

            Dep.prototype.create.call(this, options);
        },

        actionShow: function (options) {
            var id = options.id;

            if (!id) {
                throw new Espo.Exceptions.NotFound("No report id.");
            }

            var createView = model =>{
                var view = this.getViewName('result');

                this.main(view, {
                    scope: this.name,
                    model: model,
                    returnUrl: options.returnUrl,
                    returnDispatchParams: options.returnDispatchParams,
                    params: options,
                });
            };

            var model = options.model;

            if (model && model.id && model.get('type') && model.get('groupBy')) {
                createView(model);

                this.showLoadingNotification();

                model.fetch().then(() => {
                    this.hideLoadingNotification();
                });

                this.listenToOnce(this.baseController, 'action', () => {
                    model.abortLastFetch();
                });

                return;
            }

            this.getModel().then(model =>{
                model.id = id;

                this.showLoadingNotification();

                model.fetch({main: true}).then(() => {
                    createView(model);
                });

                this.listenToOnce(this.baseController, 'action', () => {
                    model.abortLastFetch();
                });
            });
        },
    });
});
