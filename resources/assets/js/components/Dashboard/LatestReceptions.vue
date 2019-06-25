<template>
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                {{ title }}
            </div>
            <div class="actions">
                <a class="btn btn-icon-only default" href="javascript:;" v-on:click.prevent="refresh()"
                   :disabled="isLoading">
                    <i class="icon-refresh"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table" id="receptions-table">
                    <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Supplier</th>
                        <th>Client</th>
                        <th>Invoice Number</th>
                        <th>Reception Date</th>
                        <th>Return</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in items">
                        <td>
                            <span v-html="item.reference"></span>
                        </td>
                        <td>
                            <span v-html="item.supplier"></span>
                        </td>
                        <td>
                            <span v-html="item.client"></span>
                        </td>
                        <td>
                            <span v-html="item.invoice_number"></span>
                        </td>
                        <td>
                            <span v-html="item.reception_date"></span>
                        </td>
                        <td>
                            <span v-html="receptionReturns(item)"></span>
                        </td>
                        <td>
                            <span v-html="item.status"></span>
                        </td>
                        <td>
                            <span v-html="item.actions"></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        props: ['title', 'url', 'company'],
        data() {
            return {
                items: [],
                isLoading: false
            }
        },
        mounted() {
            this.refresh()
        },
        methods: {
            receptionReturns(reception) {
                return reception.return ? '<span class="label label-success">Returned</span>' : '<span class="label label-info">No Return</span>'
            },
            refresh() {
                this.isLoading = true
                App.blockUI({
                    target: '#receptions-table',
                    boxed: true,
                    message: "Processing...",
                })
                let builtUrl = this.url;
                if (this.company !== null && this.company !== '') {
                    builtUrl += '?company=' + JSON.parse(this.company).id
                }
                axios.get(builtUrl)
                    .then((response) => {
                        this.items = response.data
                        this.isLoading = false
                        App.unblockUI('#receptions-table');
                    })
            }
        }
    }
</script>