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

define('advanced:views/workflow/record/detail', 'views/record/detail', function (Dep) {

    return Dep.extend({

        editModeEnabled: false,

        editModeDisabled: true,

        bottomView: 'advanced:views/workflow/record/detail-bottom',

        duplicateAction: true,

        stickButtonsContainerAllTheWay: true,

        saveAndContinueEditingAction: true,

        setup: function () {
            Dep.prototype.setup.call(this);
            this.manageFieldsVisibility();
            this.listenTo(this.model, 'change', function (model, options) {
                if (this.model.hasChanged('portalOnly') || this.model.hasChanged('type')) {
                    this.manageFieldsVisibility(options.ui);
                }
            }, this);

            if (!this.model.isNew()) {
                this.setFieldReadOnly('type');
                this.setFieldReadOnly('entityType');
            }
        },

        manageFieldsVisibility: function (ui) {
            let type = this.model.get('type');

            if (
                this.model.get('portalOnly') &&
                ~['afterRecordSaved', 'afterRecordCreated', 'afterRecordUpdated', 'signal'].indexOf(type)
            ) {
                this.showField('portal');
            } else {
                this.hideField('portal');
            }

            if (type !== 'scheduled') {
                this.hideField('targetReport');
                this.hideField('scheduling');
                this.setFieldNotRequired('targetReport');
            }

            if (type === 'manual') {
                this.hideField('portalOnly');
                this.hideField('portal');

                if (this.mode === 'edit' && ui) {
                    setTimeout(() => {
                        this.model.set({
                            'portalId': null,
                            'portalName': null,
                            'portalOnly': false
                        });
                    }, 100);
                }

                return;
            }

            if (type === 'scheduled') {
                this.showField('targetReport');
                this.showField('scheduling');
                this.setFieldRequired('targetReport');
                this.hideField('portal');
                this.hideField('portalOnly');

                if (this.mode === 'edit' && ui) {
                    setTimeout(() => {
                        this.model.set({
                            'portalId': null,
                            'portalName': null,
                            'portalOnly': false
                        });
                    }, 100);
                }

                return;
            }

            if (type === 'sequential') {
                this.hideField('portal');
                this.hideField('portalOnly');

                if (this.mode === 'edit' && ui) {
                    setTimeout(() => {
                        this.model.set({
                            'portalId': null,
                            'portalName': null,
                            'portalOnly': false
                        });
                    }, 100);
                }

                return;
            }

            if (this.model.get('portalOnly')) {
                this.showField('portal');
            }

            this.showField('portalOnly');
        },
    });
});
