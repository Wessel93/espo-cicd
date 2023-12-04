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

define('advanced:views/bpmn-flowchart-element/fields/task-send-message-reply-to',
['views/fields/enum', 'advanced:views/bpmn-flowchart-element/fields/task-send-message-from'], function (Dep, From) {

    return Dep.extend({

        setupOptions: function () {
            Dep.prototype.setupOptions.call(this);

            this.params.options = Espo.Utils.clone(this.params.options);

            if (
                this.getMetadata()
                    .get(['entityDefs', this.model.targetEntityType, 'fields', 'emailAddress', 'type']) ===
                'email'
            ) {
                this.params.options.push('targetEntity');
            }

            let linkOptionList = From.prototype.getLinkOptionList.call(this, false, true);

            linkOptionList.forEach(item => {
                this.params.options.push(item);
            });

            From.prototype.translateOptions.call(this);
        },
    });
});
