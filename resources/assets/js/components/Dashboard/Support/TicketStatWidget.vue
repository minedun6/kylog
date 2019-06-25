<template>
    <div :class="'panel panel-' + type">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3" style="font-size: 5em;">
                    <i :class="'fa ' + icon"></i>
                </div>
                <div class="col-xs-9 text-right">
                    <h1>
                        <animated-number :number="value"></animated-number>
                    </h1>
                    <div>{{ title }}</div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                required: true
            },
            count: {
                required: true,
            },
            title: {
                type: String,
                required: true
            },
            icon: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                value: null,
            }
        },
        mounted() {
            this.value = parseInt(this.count)
        }
    }
</script>