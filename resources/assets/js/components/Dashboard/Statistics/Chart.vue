<template>
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">
                {{ title }}
            </div>
            <div class="actions">
                <transition name="fadeInDown">
                    <div class="btn-group btn-group-devided" data-toggle="buttons" v-show="drilldownApplied">
                        <label class="btn btn-circle btn-transparent grey-steel" @click="change">
                            <input type="radio" name="period" :value="1" class="toggle" v-model="period"> 1M
                        </label>
                        <label class="btn btn-circle btn-transparent grey-steel" @click="change">
                            <input type="radio" name="period" :value="2" class="toggle" v-model="period"> 3M
                        </label>
                        <label class="btn btn-circle btn-transparent grey-steel" @click="change">
                            <input type="radio" name="period" :value="3" class="toggle" v-model="period"> 6M
                        </label>
                        <label class="btn btn-circle btn-transparent grey-steel" @click="change">
                            <input type="radio" name="period" :value="4" class="toggle" v-model="period"> 1Y
                        </label>
                        <label class="btn btn-circle btn-transparent grey-steel" @click="change">
                            <input type="radio" name="period" :value="5" class="toggle" v-model="period"> All
                        </label>
                    </div>
                </transition>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->
        <div class="portlet-body">
            <transition name="fadeInDown">
                <div class="row" v-show="drilldownApplied">
                    <div class="col-md-12">
                        <div class="form-group">
                        <label>Select the appropriate type of report :</label>
                            <select v-model="type" @change="changeChartType()" class="form-control">
                                <option value="pie">Pie</option>
                                <option value="column">Bar Chart</option>
                                <option value="bar">Horizontal Bar Chart</option>
                            </select>
                        </div>
                    </div>
                </div>
            </transition>
            <div :id="container">

            </div>
        </div><!-- /.box-body -->
    </div><!--box-->
</template>

<script>
    import Highcharts from 'highcharts'
    import data from 'highcharts/modules/data'
    import drilldown from 'highcharts/modules/drilldown'
    import exportService from 'highcharts/modules/exporting'
    import axios from 'axios'

    export default {
        props: ['title', 'container', 'drilldown', 'endpoint', 'chart_title', 'company', 'drilldown_endpoint', 'series_name'],
        data() {
            return {
                isProcessing: false,
                chart: null,
                period: null,
                drilldownApplied: true,
                type: 'bar'
            }
        },
        mounted() {
            if (!this.chart) {
                this.initChart()
            }
        },
        methods: {
            changeChartType() {
                this.chart = null;
                this.initChart()
            },
            change(e) {
                $(e.target.children[0]).click();
                let period = $(e.target.children[0]).val();
                if (period !== null && period !== undefined) {
                    this.getData(this.endpoint + '?period=' + period, this.chart)
                }
            },
            initChart() {
                drilldown(Highcharts);
                exportService(Highcharts);

                if (!this.chart) {
                    let vm = this;
                    this.chart = new Highcharts.chart({
                        chart: {
                            renderTo: this.container,
                            type: this.type,
                            events: {
                                load: function (e) {
                                    vm.getData(vm.endpoint, this)
                                },
                                drilldown: function (e) {
                                    vm.drilldownApplied = false;
                                    if (!e.seriesOptions) {
                                        vm.getDrilldownData(e, vm.drilldown_endpoint, this);
                                    }
                                },
                                drillup: function (e) {
                                    vm.drilldownApplied = true;
                                }
                            }
                        },
                        title: {
                            text: this.chart_title
                        },
                        credits: {
                            enabled: false
                        },
                        legend: {
                            enabled: false
                        },
                        drilldown: {
                            drillUpButton: {
                                relativeTo: 'spacingBox',
                                position: {
                                    y: 0,
                                    x: 0
                                },
                                theme: {
                                    fill: 'white',
                                    'stroke-width': 1,
                                    stroke: 'silver',
                                    r: 0,
                                    states: {
                                        hover: {
                                            fill: '#bada55'
                                        },
                                        select: {
                                            stroke: '#039',
                                            fill: '#bada55'
                                        }
                                    }
                                }

                            },
                        },
                        xAxis: {
                            type: 'category'
                        },
                        plotOptions: {
                            borderWidth: 0,
                            series: {
                                colorByPoint: true,
                                name: vm.series_name,
                                dataLabels: {
                                    enabled: true,
//                                    format: '{point.name}: {point.y:.1f}'
                                }
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b><br/>'
                        },
                    })
                }
            },
            getData(endpoint, chart) {
                chart.showLoading('Processing ...');
                axios.get(endpoint)
                    .then((response) => {
                        this.chart.hideLoading();
                        while (this.chart.series.length) {
                            this.chart.series[0].remove();
                        }
                        this.chart.colorCounter = 0;
                        this.chart.symbolCounter = 0;

                        this.chart.addSeries({data: response.data})
                    }).catch((err) => {
                    this.chart.hideLoading();
                    console.log(err.message)
                })
            },
            getDrilldownData(e, endpoint, chart) {
                let id = e.point.drilldown;
                if (!e.seriesOptions) {
                    chart.showLoading('Processing ...');
                    axios.get(endpoint + '?drilldown=' + id)
                        .then((response) => {
                            chart.hideLoading();
                            chart.addSeriesAsDrilldown(e.point, response.data);
                        }).catch((err) => {
                        chart.hideLoading();
                        console.log(err.message)
                    })
                }
            }
        }
    }
</script>