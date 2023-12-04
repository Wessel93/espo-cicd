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

define('advanced:views/target-list/record/panels/relationship',
['crm:views/target-list/record/panels/relationship'], function (Dep) {

    return Dep.extend({

        actionPopulateFromReport: function (data) {
            let link = data.link;

            let filterName = 'list' + Espo.Utils.upperCaseFirst(link);

            Espo.Ui.notify(' ... ');

            this.createView('dialog', 'views/modals/select-records', {
                scope: 'Report',
                multiple: false,
                createButton: false,
                primaryFilterName: filterName,
            }, view => {
                view.render();

                Espo.Ui.notify(false);

                this.listenToOnce(view, 'select', select => {
                    Espo.Ajax
                        .postRequest('Report/action/populateTargetList', {
                            id: select.id,
                            targetListId: this.model.id,
                        })
                        .then(() => {
                            Espo.Ui.success(this.translate('Linked'));

                            this.collection.fetch();
                        });
                });
            });
        },
    });
});
