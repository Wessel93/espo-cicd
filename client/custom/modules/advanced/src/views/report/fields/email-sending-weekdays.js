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

define('advanced:views/report/fields/email-sending-weekdays', ['views/fields/base'], function (Dep) {

    return Dep.extend({

        editTemplate: 'advanced:report/fields/email-sending-weekdays/edit',
        detailTemplate: 'advanced:report/fields/email-sending-weekdays/detail',

        afterRender: function () {
            if (this.mode === 'edit' || this.mode === 'search') {
                this.$element = this.$el.find('input[data-name="'+this.name+'"]');

                if (this.mode === 'edit') {
                    this.$element.on('change', () => {
                        this.trigger('change');
                    });
                }
            }
        },

        data: function () {
            var weekday = this.model.get(this.name) || '';
            var weekdays = {};

            for (let i = 0; i < 7; i++) {
                weekdays[i] = (weekday.indexOf(i.toString())) > -1 || false;
            }

            return _.extend({
                selectedWeekdays: weekdays,
                days: this.translate('dayNamesShort', 'lists'),
            }, Dep.prototype.data.call(this));
        },

        fetch: function () {
            var data = {};
            var value = '';

            this.$element.each(function () {
                if ($(this).is(':checked')) {
                    value += $(this).val();
                }
            });

            data[this.name] = value;

            return data;
        },
    });
});
