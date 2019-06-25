<template>
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">
                {{ portletTitle }}
            </div>
            <div class="actions">
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-offset-2 col-md-4">
                    <div class="form-group">
                        <select id="year" class="form-control">
                            <option value=""></option>
                        </select>
                        <input type="hidden" v-model="year">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <select id="company-select" class="form-control">
                            <option value=""></option>
                        </select>
                        <input type="hidden" v-model="selectdCompany">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="year"></label>
                        <button :disabled="isLoading"
                                @click.prevent="getAllData()" class="btn default btn-sm">
                            <i class="fa fa-filter" v-show="!isLoading"></i>
                            <i v-show="isLoading" class="fa fa-spinner fa-spin"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-2 col-md-4">
                    <table class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th colspan="3" style="text-align: center">Entrée</th>
                        </tr>
                        <tr>
                            <th style="text-align: center">Mois</th>
                            <th style="text-align: center">Num Palettes</th>
                            <th style="text-align: center">Num Pièces</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="rowStat in received">
                            <td style="text-align: center">{{ rowStat.month }}</td>
                            <td style="text-align: center">{{ rowStat.packages_number }}</td>
                            <td style="text-align: center">{{ rowStat.pieces_number }}</td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th style="text-align: center">Total</th>
                            <td style="text-align: center">{{ totalPackagesReceived }}</td>
                            <td style="text-align: center">{{ totalPiecesReceived }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th colspan="3" style="text-align: center">Sortie</th>
                        </tr>
                        <tr>
                            <th style="text-align: center">Mois</th>
                            <th style="text-align: center">Num Palettes</th>
                            <th style="text-align: center">Num Pièces</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="rowStat in delivered">
                            <td style="text-align: center">{{ rowStat.month }}</td>
                            <td style="text-align: center">{{ rowStat.packages_number }}</td>
                            <td style="text-align: center">{{ rowStat.pieces_number }}</td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th style="text-align: center">Total</th>
                            <td style="text-align: center">{{ totalPackagesDelivered }}</td>
                            <td style="text-align: center">{{ totalPiecesDelivered }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div id="cont1"></div>
                </div>
                <div class="col-md-6">
                    <div id="cont2"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div id="cont3"></div>
                </div>
                <div class="col-md-6">
                    <div id="cont4"></div>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!--box-->
</template>

<script>
    import axios from 'axios'
    import Highcharts from 'highcharts'
    import data from 'highcharts/modules/data'

    export default {
        props: ['source'],
        data() {
            return {
                chartForReceivedPackages: null,
                chartForDeliveredPackages: null,
                chartForReceivedPieces: null,
                chartForDeliveredPieces: null,
                years: ['2015', '2016', '2017', '2018', '2019', '2020'],
                selectedCompany: '',
                year: 2017,
                totalPackagesReceived: 0,
                totalPiecesReceived: 0,
                totalPackagesDelivered: 0,
                totalPiecesDelivered: 0,
                received: [],
                delivered: [],
                receivedPackagesDataForChart: [],
                receivedPiecesDataForChart: [],
                deliveredPackagesDataForChart: [],
                deliveredPiecesDataForChart: [],
                isLoading: false,
            }
        },
        computed: {
            portletTitle: function () {
                if (this.selectedCompany !== undefined || this.year !== undefined) {
                    return 'Movement ' + $('#company-select option:selected').text() + ' ' + $('#year option:selected').text();
                }
            }
        },
        mounted() {
            let self = this;
            let el = $('#company-select');
            let yearSelect = $('#year');
            el.select2({
                placeholder: 'Choose a company ...',
                theme: "bootstrap",
                allowClear: true,
                width: null,
                ajax: {
                    url: '/api/companies/get',
                    method: 'get',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                },
                cache: true
            }).on('change', function () {
                self.selectedCompany = el.val();
            });
            yearSelect.select2({
                placeholder: 'Select A Year ...',
                theme: "bootstrap",
                allowClear: true,
                width: null,
                data: this.years
            }).on('change', function () {
                self.year = yearSelect.val();
            });

        },
        methods: {
            getAllData: function () {
                this.isLoading = true;
                axios.get(this.source + '?company=' + this.selectedCompany + '&year=' + this.year)
                    .then((response) => {
                        this.isLoading = false;
                        this.received = response.data.received;
                        this.delivered = response.data.delivered;
                        this.totalPackagesDelivered = response.data.totalPackagesDelivered;
                        this.totalPiecesDelivered = response.data.totalPiecesDelivered;
                        this.totalPackagesReceived = response.data.totalPackagesReceived;
                        this.totalPiecesReceived = response.data.totalPiecesReceived;
                        this.receivedPackagesDataForChart = response.data.ReceivedPackageDataForChart;
                        this.deliveredPackagesDataForChart = response.data.DeliveredPackageDataForChart;
                        this.deliveredPiecesDataForChart = response.data.DeliveredPiecesDataForChart;
                        this.receivedPiecesDataForChart = response.data.ReceivedPiecesDataForChart;
                        this.initChart(this.chartForReceivedPackages, 'cont1', this.receivedPackagesDataForChart, 'Received Packages');
                        this.initChart(this.chartForDeliveredPackages, 'cont2', this.deliveredPackagesDataForChart, 'Delivered Packages');
                        this.initChart(this.chartForReceivedPieces, 'cont3', this.receivedPiecesDataForChart, 'Received Pieces');
                        this.initChart(this.chartForDeliveredPieces, 'cont4', this.deliveredPiecesDataForChart, 'Delivered Pieces');
                    }).catch((err) => {
                    this.isLoading = false;
                })
            },
            initChart: function (chart, container, data, title) {
                chart = new Highcharts.chart({
                    chart: {
                        renderTo: container,
                        type: 'pie'
                    },
                    series: [{
                        name: 'Received Packages',
                        colorByPoint: true,
                        data: data
                    }],
                    title: {
                        text: title
                    },
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled: false
                    },
                    xAxis: {
                        type: 'category'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.y:.2f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                }
                            }
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f} %</b><br/>'
                    },
                })
            }
        }
    }
</script>