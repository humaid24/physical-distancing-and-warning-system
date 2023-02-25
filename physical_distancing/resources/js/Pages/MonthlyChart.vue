<script>
import axios from "axios";
import { defineComponent } from "vue";
import { Bar } from "vue3-chart-v2";
import _ from "lodash";
export default defineComponent({
    name: "MonthlyChart",
    extends: Bar,
    props: ["param_selector"],
    methods: {
        getViolationChartData(data_input) {
            axios
                .post("chartData", { data_input })
                .then(({ data, status }) => {
                    this.renderChart({
                        labels: Object.keys(data),
                        datasets: [
                            {
                                label: "Physical Distancing Violations",
                                backgroundColor: "hsl(2, 100%, 42%)",
                                data: Object.values(data),
                            },
                        ],
                    });
                })
                .catch((error) => {});
        },
    },
    watch: {
        param_selector: _.debounce(function (data) {
            this.getViolationChartData(data);
        }),
    },
    mounted() {
        this.getViolationChartData(this.param_selector);
    },
});
</script>
