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

define('advanced:views/bpmn-process/record/detail', ['views/record/detail'], function (Dep) {

    return Dep.extend({

        duplicateAction: false,

        setup: function () {
            Dep.prototype.setup.call(this);

            this.hideField('startElementId');

            if (this.getAcl().checkModel(this.model, 'edit')) {
                this.dropdownItemList.push({
                    'label': 'Stop Process',
                    'name': 'stopProcess',
                    'hidden': !this.isStoppable(),
                });

                this.dropdownItemList.push({
                    'label': 'Reactivate',
                    'name': 'reactivate',
                    'hidden': !this.isReactivatable(),
                });

                this.listenTo(this.model, 'sync', () => this.controlActions());
            }

            this.dropdownItemList.push({
                'label': 'View Variables',
                'name': 'viewVariables',
            });
        },

        controlActions: function () {
            this.isStoppable() ?
                this.showActionItem('stopProcess') :
                this.hideActionItem('stopProcess');

            this.isReactivatable() ?
                this.showActionItem('reactivate') :
                this.hideActionItem('reactivate');
        },

        isReactivatable: function () {
            return ['Ended', 'Stopped', 'Interrupted'].includes(this.model.get('status'));
        },

        isStoppable: function () {
            return ['Started', 'Paused'].includes(this.model.get('status'));
        },

        actionStopProcess: function () {
            if (!this.isStoppable()) {
                console.error('Cannot stop. Not appropriate status.');

                return;
            }

            this.confirm(this.translate('confirmation', 'messages'), () => {
                Espo.Ajax
                    .postRequest('BpmnProcess/action/stop', {id: this.model.id})
                    .then(() => {
                        this.model.set('status', 'Stopped');
                        Espo.Ui.success(this.translate('Done', 'labels'));
                        this.hideActionItem('stopProcess');

                        this.model.trigger('after:relate');
                        this.model.fetch();
                    });
            });
        },

        actionReactivate: function () {
            if (!this.isReactivatable()) {
                console.error('Cannot reactivate. Not appropriate status.');

                return;
            }

            this.confirm(this.translate('confirmation', 'messages'), () => {
                Espo.Ajax
                    .postRequest('BpmnProcess/action/reactivate', {id: this.model.id})
                    .then(() => {
                        Espo.Ui.success(this.translate('Done', 'labels'));

                        this.model.trigger('after:relate');
                        this.model.fetch();
                    });
            });
        },

        actionViewVariables: function () {
            this.createView('dialog', 'advanced:views/bpmn-process/modals/view-variables', {model: this.model})
                .then(view => {
                    view.render();
                });
        },
    });
});
