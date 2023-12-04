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

define('advanced:views/workflow/fields/target-user-position', ['views/fields/enum'], function (Dep) {

    return Dep.extend({

        setup: function () {
            Dep.prototype.setup.call(this);

            this.translatedOptions = {
                '': '--' + this.translate('All') + '--',
            };

            this.params.options = [''];

            if (this.model.get('targetUserPosition') && this.model.get('targetTeamId')) {
                this.params.options.push(this.model.get('targetUserPosition'));
            }

            this.loadRoleList(() => {
                if (this.mode === 'edit') {
                    if (this.isRendered()) {
                        this.render();
                    }
                }
            });

            this.listenTo(this.model, 'change:targetTeamId', () => {
                this.loadRoleList(() => {
                    this.render();
                });
            });
        },

        loadRoleList: function (callback, context) {
            var teamId = this.model.get('targetTeamId');

            if (!teamId) {
                this.params.options = [''];
            }

            this.getModelFactory().create('Team', team => {
                team.id = teamId;

                this.listenToOnce(team, 'sync', () => {
                    this.params.options = team.get('positionList') || [];
                    this.params.options.unshift('');

                    callback.call(context);
                });

                team.fetch();
            });
        },
    });
});
