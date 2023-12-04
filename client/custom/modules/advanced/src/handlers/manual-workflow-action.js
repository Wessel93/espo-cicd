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

define('advanced:handlers/manual-workflow-action', ['action-handler'], function (Dep) {

    return Dep.extend({

        actionRunWorkflow: function (data) {
            /** @type {module:views/detail.Class} */
            let view = this.view;
            let id = data.id;
            let name = 'runWorkflow_' + id;

            Espo.Ui
                .confirm(view.translate('confirmation', 'messages'), {
                    confirmText: view.translate('Yes', 'labels'),
                    cancelText: view.translate('No', 'labels'),
                })
                .then(() => {
                    /** @type {module:model.Class} */
                    let model = this.view.model;

                    view.disableMenuItem(name);

                    Espo.Ui.notify(' ... ');

                    Espo.Ajax
                        .postRequest('WorkflowManual/action/run', {
                            targetId: model.id,
                            id: id,
                        })
                        .then(() => {
                            model.fetch()
                                .then(() => {
                                    Espo.Ui.success(view.translate('Done'));

                                    view.enableMenuItem(name);
                                });
                        })
                        .catch(() => {
                            view.enableMenuItem(name);
                        });
                });
        },
    });
});
