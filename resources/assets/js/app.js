require('./bootstrap');

Vue.component('animated-number', require('./components/Dashboard/AnimatedNumber.vue'));
Vue.component('widget-thumb', require('./components/Dashboard/WidgetThumb.vue'));
Vue.component('latest-receptions', require('./components/Dashboard/LatestReceptions.vue'));
Vue.component('latest-deliveries', require('./components/Dashboard/LatestDeliveries.vue'));
Vue.component('chart', require('./components/Dashboard/Statistics/Chart.vue'));
Vue.component('stats', require('./components/Dashboard/Statistics/Stats.vue'));
Vue.component('pie', require('./components/Dashboard/Statistics/PieChart.vue'));
Vue.component('ticket-stat-widget', require('./components/Dashboard/Support/TicketStatWidget.vue'));
Vue.component('drive-picker', require('./components/General/GoogleDrivePicker.vue'));

