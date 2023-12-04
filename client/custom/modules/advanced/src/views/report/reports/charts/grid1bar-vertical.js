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

define('advanced:views/report/reports/charts/grid1bar-vertical',
['advanced:views/report/reports/charts/grid2bar-vertical'], function (Dep) {

    return Dep.extend({

        noLegend: true,

        columnWidth: 60,

        barWidth: 0.5,

        legendColumnWidth: 160,

        pointXHalfWidth: 0.5,

        init: function () {
            Dep.prototype.init.call(this);
        },

        prepareData: function () {
            var result = this.result;
            var grList = this.grList = result.grouping[0];

            if (this.options.color) {
                this.colorList = Espo.Utils.clone(this.colorList);
                this.colorList[0] = this.options.color;
            }

            var columnList = this.columnList || [this.column];

            var baseShift, middleIndex;

            if (this.columnList) {
                this.barWidth = 1 / (this.columnList.length) * 0.65;
                baseShift = 1 / this.columnList.length;
                middleIndex = Math.ceil(this.columnList.length / 2) - 1;

                this.noLegend = false;
            }

            var max = 0;
            var max2 = 0;

            var min = 0;
            var min2 = 0;

            var chartData = [];

            columnList.forEach((column, j) => {
                var columnData = {
                    data: [],
                    label: this.reportHelper.formatColumn(column, this.result),
                    column: column,
                };

                var shift = 0;

                if (this.columnList) {
                    if (!this.isLine) {
                        var diffIndex = j - middleIndex;

                        shift = baseShift * diffIndex;

                        if (this.columnList.length % 2 === 0) {
                            shift -= baseShift / 2;
                        }

                        shift *= 0.75;
                    }

                    if (this.secondColumnList && ~this.secondColumnList.indexOf(column)) {
                        columnData.yaxis = 2;
                    }
                }

                columnData.value = 0;

                grList.forEach((group, i) => {
                    var value = (this.result.reportData[group] || {})[column] || 0;

                    if (this.secondColumnList && ~this.secondColumnList.indexOf(column)) {
                        if (value > max2) {
                            max2 = value;
                        }

                        if (value < min2) {
                            min2 = value;
                        }
                    } else {
                        if (value > max) {
                            max = value;
                        }

                        if (value < min) {
                            min = value;
                        }
                    }

                    columnData.data.push([i + shift, value]);

                    columnData.value += value;
                });

                if (column in this.colors) {
                    columnData.color = this.colors[column];
                }

                chartData.push(columnData);
            });

            this.max = max;
            this.max2 = max2;

            this.min = min;
            this.min2 = min2;

            this.chartData = chartData;
        },

        getTickNumber: function () {
            var containerWidth = this.$container.width();

            return Math.floor(containerWidth / this.columnWidth);
        },

        isNoData: function () {
            if (!this.chartData.length) {
                return true;
            }

            let isEmpty = true;

            for (let item of this.chartData) {
                if (
                    item &&
                    item.data &&
                    item.data.length &&
                    item.value
                ) {
                    isEmpty = false;

                    break;
                }
            }

            return isEmpty;
        },

        getHorizontalPointCount: function () {
            return this.grList.length;
        },

        draw: function () {
            if (this.$container.height() === 0) {
                this.$container.empty();

                return;
            }

            if (this.isNoData()) {
                this.showNoData();

                return;
            }

            var tickNumber = this.getTickNumber();

            this.$graph = this.flotr.draw(this.$container.get(0), this.chartData, {
                shadowSize: false,
                colors: this.colorList,
                bars: {
                    show: true,
                    horizontal: false,
                    shadowSize: 0,
                    lineWidth: 1,
                    fillOpacity: 1,
                    barWidth: this.barWidth,
                },
                grid: {
                    horizontalLines: true,
                    verticalLines: false,
                    outline: 'sw',
                    color: this.gridColor,
                    tickColor: this.tickColor,
                },
                yaxis: {
                    min: this.min + 0.08 * this.min,
                    showLabels: true,
                    color: this.textColor,
                    max: this.max + 0.08 * this.max,
                    tickFormatter: value => {
                        if (value == 0 && this.min === 0) {
                            return '';
                        }

                        if (value > this.max + 0.072 * this.max) {
                            return '';
                        }

                        if (value % 1 == 0) {
                            return this.formatNumber(Math.floor(value), this.isCurrency, true, true, true)
                                .toString();
                        }

                        return '';
                    },
                },
                y2axis: {
                    min: this.min2 + 0.08 * this.min2,
                    showLabels: true,
                    color: this.textColor,
                    max: this.max2 + 0.08 * this.max2,
                    tickFormatter: value => {
                        if (value == 0 && this.min2 === 0) {
                            return '';
                        }

                        if (value > this.max2 + 0.07 * this.max2) {
                            return '';
                        }

                        if (value % 1 == 0) {
                            return this.formatNumber(Math.floor(value), false, true, true)
                                .toString();
                        }

                        return '';
                    },
                },
                xaxis: {
                    min: this.xMin || 0,
                    max: this.xMax || null,
                    noTicks: tickNumber,
                    color: this.textColor,
                    tickFormatter: value => {
                        if (value % 1 == 0) {
                            var i = parseInt(value);

                            if (i in this.grList) {
                                if (this.grList.length - tickNumber > 5 && i === this.grList.length - 1 && !this.isZoomed) {
                                    return '';
                                }

                                return this.formatGroup(0, this.grList[i]);
                            }
                        }
                        return '';
                    },
                },
                mouse: {
                    track: true,
                    relative: true,
                    position: 's',
                    lineColor: this.hoverColor,
                    autoPositionVertical: true,
                    cursorPointer: true,
                    trackFormatter: obj => {
                        var i = obj.index;
                        var column = obj.series.column;
                        var string = this.formatGroup(0, this.grList[i]);

                        if (this.columnList) {
                            if (string) {
                                string += '<br>';
                            }

                            string += obj.series.label;
                        }

                        if (string) {
                            string += '<br>';
                        }

                        string += this.formatCellValue(obj.y, column);

                        return string;
                    },
                },
                legend: {
                    show: this.columnList,
                    noColumns: this.getLegendColumnNumber(),
                    container: this.$el.find('.legend-container'),
                    labelBoxMargin: 0,
                    labelFormatter: this.labelFormatter.bind(this),
                    labelBoxBorderColor: 'transparent',
                    backgroundOpacity: 0,
                },
            });

            if (this.columnList) {
                this.adjustLegend();
            }

            if (this.dragStart) {
                return;
            }

            Flotr.EventAdapter.observe(this.$container.get(0), 'flotr:click', position => {
                if (!position.hit) {
                    return;
                }

                if (!('index' in position.hit)) {
                    return;
                }

                var column = null;

                if (this.result.isJoint) {
                    if (this.columnList) {
                        column = this.columnList[position.hit.seriesIndex];
                    }
                    else {
                        column = this.column;
                    }
                }

                this.trigger('click-group', this.grList[position.hit.index], undefined, undefined, column);
            });
        },
    });
});
