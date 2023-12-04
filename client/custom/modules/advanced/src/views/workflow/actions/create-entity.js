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

define('advanced:views/workflow/actions/create-entity', ['advanced:views/workflow/actions/base', 'model'],
function (Dep, Model) {

    return Dep.extend({

        type: 'createEntity',

        defaultActionData: {
            link: false,
            fieldList: [],
            fields: {},
        },

        data: function () {
            var data = Dep.prototype.data.call(this);

            data.numberId = this.numberId;
            data.aliasId = this.aliasId;

            return data;
        },

        setup: function () {
            Dep.prototype.setup.call(this);

            this.numberId = null;

            if (this.options.flowchartElementId && this.options.flowchartCreatedEntitiesData) {
                var aliasId = this.options.flowchartElementId + '_' + this.actionData.id;

                if (aliasId in this.options.flowchartCreatedEntitiesData) {
                    this.numberId = this.options.flowchartCreatedEntitiesData[aliasId].numberId;

                }

                this.aliasId = aliasId;
            }
        },

        additionalSetup: function() {
            Dep.prototype.additionalSetup.call(this);

            this.displayedLinkedEntityName = this.translate(this.actionData.link, 'scopeNames');
        },

        afterRender: function () {
            Dep.prototype.afterRender.call(this);

            if (this.type === 'createEntity') {
                var model = new Model;

                model.set('linkList', this.actionData.linkList);

                var translatedOptions = {};

                (this.actionData.linkList || []).forEach(link => {
                    translatedOptions[link] = this.getLanguage().translate(link, 'links', this.actionData.link);
                });

                this.createView('linkList', 'views/fields/multi-enum', {
                    model: model,
                    el: this.getSelector() + ' .field[data-name="linkList"]',
                    mode: 'detail',
                    defs: {
                        name: 'linkList'
                    },
                    inlineEditDisabled: true,
                    translatedOptions: translatedOptions,
                }, view => {
                    view.render();
                });
            }
        },
    });
});

