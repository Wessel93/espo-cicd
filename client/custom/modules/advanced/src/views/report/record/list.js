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

define('advanced:views/report/record/list', ['views/record/list'], function (Dep) {

    return Dep.extend({

        quickEditDisabled: true,

        mergeAction: false,

        massActionList: ['remove', 'massUpdate', 'export'],

        rowActionsView: 'advanced:views/report/record/row-actions/default',

        massPrintPdfDisabled: true,

        actionShow: function (data) {
            if (!data.id) {
                return;
            }

            let model = this.collection.get(data.id);

            if (!model) {
                return;
            }

            this.createView('resultModal', 'advanced:views/report/modals/result', {
                model: model
            }, (view) => {
                view.render();

                this.listenToOnce(view, 'navigate-to-detail', (model) => {
                    let options = {
                        id: model.id,
                        model: model,
                        rootUrl: this.getRouter().getCurrentUrl(),
                    };

                    this.getRouter().navigate('#Report/view/' + model.id, {trigger: false});
                    this.getRouter().dispatch('Report', 'view', options);

                    view.close();
                });
            });
        },
    });
});
