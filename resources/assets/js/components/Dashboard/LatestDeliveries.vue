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
                <table class="table" id="deliveries-table">
                    <thead>
                    <tr>
                        <th>Delivery Number</th>
                        <th>Suppliers</th>
                        <th>Client</th>
                        <th>Delivery Order Date</th>
                        <th>Destination</th>
                        <th>Final Destination</th>
                        <th>PO</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in items">
                        <td>
                            <span v-html="item.delivery_number"></span>
                        </td>
                        <td>
                            <span v-html="item.suppliers"></span>
                        </td>
                        <td>
                            <span v-html="item.client"></span>
                        </td>
                        <td>
                            <span v-html="item.delivery_order_date"></span>
                        </td>
                        <td>
                            <span v-html="item.destination"></span>
                        </td>
                        <td>
                            <span v-html="item.final_destination"></span>
                        </td>
                        <td>
                            <span v-html="item.po"></span>
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
            refresh() {
                this.isLoading = true
                App.blockUI({
                    target: '#deliveries-table',
                    boxed: true,
                    message: "Processing..."
                })

                let builtUrl = this.url;
                if (this.company !== null && this.company !== '') {
                    builtUrl += '?company=' + JSON.parse(this.company).id
                }
                axios.get(builtUrl)
                    .then((response) => {
                        this.items = response.data
                        this.isLoading = false
                        App.unblockUI('#deliveries-table');
                    })
            }
        }
    }
</script>