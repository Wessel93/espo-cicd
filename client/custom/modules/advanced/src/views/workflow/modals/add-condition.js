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

define('advanced:views/workflow/modals/add-condition', ['views/modal'], function (Dep) {

    return Dep.extend({

        templateContent: '<div class="field" data-name="conditionFields">{{{field}}}</div>',

        backdrop: true,

        events: {
            'click a[data-action="addField"]': function (e) {
                this.trigger('add-field', $(e.currentTarget).data().name);
            }
        },

        setup: function () {
            this.header = this.translate('Add Condition', 'labels', 'Workflow');

            var scope = this.scope = this.options.scope;

            this.wait(true);

            this.getModelFactory().create('Workflow', model => {
                model.targetEntityType = scope;

                this.createView('field', 'advanced:views/workflow/fields/condition-fields', {
                    el: this.getSelector() + ' .field',
                    model: model,
                    mode: 'edit',
                    createdEntitiesData: this.options.createdEntitiesData,
                    defs: {
                        name: 'conditionFields',
                        params: {},
                    },
                }, view => {
                    this.listenTo(view, 'change', () => {
                        var list = model.get('conditionFields') || [];

                        if (!list.length) {
                            return;
                        }

                        this.trigger('add-field', list[0]);
                    });
                });

                this.wait(false);
            });
        },
    });
});

