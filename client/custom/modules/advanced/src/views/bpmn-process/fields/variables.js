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

define('advanced:views/bpmn-process/fields/variables', ['views/fields/text'], function (Dep) {

    return Dep.extend({

        detailTemplateContent: `
            {{#if isSet}}
            <div class="complex-text">
                <pre><code>{{value}}</code></pre>
            </div>            
            {{else}}
            <span class="loading-value">...</span>
            {{/if}}
        `,

        data: function () {
            if (!this.model.has(this.name)) {
                return {};
            }

            let value = this.model.get(this.name);

            let stringValue = JSON.stringify(value, null, 4);

            return {
                isSet: true,
                value: stringValue,
            };
        },
    });
});
