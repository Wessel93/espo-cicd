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

define('advanced:views/workflow/record/edit',
['views/record/edit', 'advanced:views/workflow/record/detail'], function (Dep, Detail) {

    return Dep.extend({

        bottomView: 'advanced:views/workflow/record/edit-bottom',
        sideView: 'advanced:views/workflow/record/edit-side',

        stickButtonsContainerAllTheWay: true,
        saveAndContinueEditingAction: true,

        fetch: function () {
            let data = Dep.prototype.fetch.call(this);

            let conditionsData = this.fetchConditions();

            for (let k in conditionsData) {
                data[k] = conditionsData[k];
            }

            let actionsData = this.fetchActions();

            for (let k in actionsData) {
                data[k] = actionsData[k];
            }

            return data;
        },

        fetchConditions: function () {
            let data = {};

            let conditionsView = this.getView('bottom').getView('conditions');

            let conditions = {};

            if (conditionsView) {
                conditions = conditionsView.fetch();
            }

            if (conditions === null) {
                return data;
            }

            data.conditionsAny = conditions.any || [];
            data.conditionsAll = conditions.all || [];
            data.conditionsFormula = conditions.formula || null;

            return data;
        },

        fetchActions: function () {
            let data = {};

            let actionsView = this.getView('bottom').getView('actions');

            let actions;

            if (actionsView) {
                actions = actionsView.fetch();
            }

            data.actions = actions;

            return data;
        },

        onChangeConditions: function () {
            let data = this.fetchConditions();

            this.model.set(data, {ui: true});
        },

        onChangeActions: function () {
            let data = this.fetchActions();

            this.model.set(data, {ui: true});
        },

        setup: function () {
            Dep.prototype.setup.call(this);
            Detail.prototype.manageFieldsVisibility.call(this);

            this.listenTo(this.model, 'change', (model, options) => {
                if (
                    this.model.hasChanged('portalOnly') ||
                    this.model.hasChanged('type')
                ) {
                    Detail.prototype.manageFieldsVisibility.call(this, options.ui);
                }
            });

            this.listenTo(this.model, 'change:entityType', (model, value, o) => {
                if (o.ui) {
                    setTimeout(() => {
                        model.set({
                            'targetReportId': null,
                            'targetReportName': null,
                        });
                    }, 100);
                }
            });

            if (!this.model.isNew()) {
                this.setFieldReadOnly('type');
                this.setFieldReadOnly('entityType');
            }

            this.listenTo(this.model, 'change', (model, o) => {
                if (
                    !this.model.hasChanged('actions') &&
                    !this.model.hasChanged('conditionsAll') &&
                    !this.model.hasChanged('conditionsAny')
                ) {
                    return;
                }

                if (!this.model.isNew()) {
                    return;
                }

                let actions = this.model.get('actions') || [];
                let conditionsAll = this.model.get('conditionsAll') || [];
                let conditionsAny = this.model.get('conditionsAny') || [];

                if (
                    actions.length ||
                    conditionsAll.length ||
                    conditionsAny.length
                ) {
                    this.setFieldReadOnly('entityType');

                    return;
                }

                this.setFieldNotReadOnly('entityType');
            });
        },
    });
});
