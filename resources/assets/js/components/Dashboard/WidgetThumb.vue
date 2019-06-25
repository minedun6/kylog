<template>
    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 margin-right-10 bordered" v-cloak>
        <h4 class="widget-thumb-heading">
            <span class="pull-right">
                <span class="btn-group">
                    <button class="btn btn-xs" v-on:click.prevent="getNextMonth()">
                        <i class="fa fa-chevron-up"></i>
                    </button>
                    <button class="btn btn-xs" v-on:click.prevent="getPrevMonth()">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <button class="btn btn-xs" v-on:click.prevent="getReceptionsByMonth(month, year)">
                        <i class="fa fa-refresh"></i>
                    </button>
                </span>
            </span>
            {{ title }}
        </h4>
        <div class="widget-thumb-wrap">
            <i :class="['widget-thumb-icon', bgColor + ' ' + icon]"></i>
            <div class="widget-thumb-body">
                <span class="widget-thumb-subtitle">{{ formattedMonth }}</span>
                <animated-number :number="value"></animated-number>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import moment from 'moment'

    export default {
        props: ['title', 'source', 'icon', 'company', 'bg-color'],
        data() {
            return {
                value: null,
                month: moment().format('MM'),
                year: moment().year()
            }
        },
        computed: {
            formattedMonth: function () {
                return this.month + ', ' + this.year
            }
        },
        mounted() {
            this.getReceptionsByMonth(this.month, this.year)
        },
        methods: {
            getNextMonth() {
                if (this.month < 12) {
                    this.month++
                    this.getReceptionsByMonth(this.month, this.year)
                } else {
                    this.month = 1
                    this.year++
                    this.getReceptionsByMonth(this.month, this.year)
                }
            },
            getPrevMonth() {
                if (this.month > 1) {
                    this.month--
                    this.getReceptionsByMonth(this.month, this.year)
                } else {
                    this.month = 12
                    this.year--
                    this.getReceptionsByMonth(this.month, this.year)
                }
            },
            getReceptionsByMonth(month, year) {
                let builtUrl = this.source + '?month=' + month + '&year=' + year;
                if (this.company !== null && this.company !== '') {
                    builtUrl += '&company=' + JSON.parse(this.company).id
                }
                axios.get(builtUrl)
                    .then(response => {
                        this.value = response.data
                    })
            }
        }
    }
</script>