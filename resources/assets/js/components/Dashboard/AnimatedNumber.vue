<template>
    <span class="widget-thumb-body-stat">
        {{ displayNumber }}
    </span>
</template>

<script>
    export default {
        props: {'number': {default: 0}},
        data: function () {
            return {
                displayNumber: 0,
                interval: false,
                delay: 20
            }
        },
        ready: function () {
            this.displayNumber = this.number ? this.number : 0;
        },
        watch: {
            number: function () {
                clearInterval(this.interval);
                if (this.number === this.displayNumber) {
                    return;
                }
                this.interval = window.setInterval(function () {
                    if (this.displayNumber !== this.number) {
                        let change = (this.number - this.displayNumber) / 10;
                        change = change >= 0 ? Math.ceil(change) : Math.floor(change);
                        this.displayNumber = this.displayNumber + change;
                    }
                }.bind(this), this.delay);
            }
        }
    }
</script>