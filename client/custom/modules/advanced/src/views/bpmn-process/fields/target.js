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

define('advanced:views/bpmn-process/fields/target',
['views/fields/link-parent'], function (Dep) {

    return Dep.extend({

        setup: function () {
            this.params.entityList = ['BpmnProcess'];

            Dep.prototype.setup.call(this);

            if (this.model.isNew() && this.mode !== 'search') {
                this.setupForeignScope();

                this.listenTo(this.model, 'change:targetType', () => {
                    this.setupForeignScope();
                    this.reRender();
                });
            } else {
                var scopes = this.getMetadata().get('scopes');
                var entityListToIgnore = this.getMetadata().get('entityDefs.Workflow.entityListToIgnore') || [];

                this.foreignScopeList = Object.keys(scopes)
                    .filter(scope => {
                        if (~entityListToIgnore.indexOf(scope)) {
                            return;
                        }

                        var defs = scopes[scope];

                        return (defs.entity && (defs.tab || defs.object || defs.workflow));
                    })
                    .sort((v1, v2) => {
                        return this.translate(v1, 'scopeNamesPlural')
                            .localeCompare(this.translate(v2, 'scopeNamesPlural'));
                    });
            }
        },

        setupForeignScope: function () {
            if (this.model.get('targetType')) {
                this.foreignScopeList = [this.model.get('targetType')];
            } else {
                this.foreignScopeList = ['BpmnProcess'];
            }
        },
    });
});
