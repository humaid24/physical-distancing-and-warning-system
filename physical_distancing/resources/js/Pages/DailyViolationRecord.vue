<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-4">
        <div
            class="
                flex
                lg:ml-auto
                mb-10
                md:mr-0
                mx-auto
                items-center
                flex-shrink-0
            "
        >
            <select
                id="country"
                name="country"
                autocomplete="country-name"
                class="
                    mt-1
                    ml-1
                    block
                    w-lg
                    border border-gray-300
                    bg-white
                    rounded-md
                    shadow-sm
                    focus:outline-none
                    focus:ring-indigo-500
                    focus:border-indigo-500
                    sm:text-sm
                "
                v-model="param_month"
                v-on:change="getDailyViolations"
            >
                <option v-for="(data, i) in monthly_arr" :key="i" :value="i">
                    {{ data }}
                </option>
            </select>
            <select
                id="country"
                name="country"
                autocomplete="country-name"
                class="
                    mt-1
                    ml-1
                    block
                    w-lg
                    border border-gray-300
                    bg-white
                    rounded-md
                    shadow-sm
                    focus:outline-none
                    focus:ring-indigo-500
                    focus:border-indigo-500
                    sm:text-sm
                "
                v-model="param_year"
                v-on:change="getDailyViolations"
            >
                <option v-for="(data, i) in yearly_arr" :key="i" :value="data">
                    {{ data }}
                </option>
            </select>
        </div>
        <div class="flex flex-wrap -m-4">
            <div
                class="md:w-1/3 md:w-1/4 p-3"
                v-for="(data, i) in data_result"
                :key="i"
            >
                <div class="border border-gray-200 p-6 rounded-lg">
                    <div
                        class="
                            w-10
                            h-10
                            inline-flex
                            items-center
                            justify-center
                            rounded-full
                            bg-indigo-100
                            text-indigo-500
                            mb-3
                        "
                    >
                        <svg
                            fill="none"
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            class="w-6 h-6"
                            viewBox="0 0 24 24"
                        >
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                        </svg>
                    </div>
                    <h2
                        class="
                            text-lg text-gray-900
                            font-medium
                            title-font
                            mb-2
                        "
                    >
                        {{ data.dt_format }}
                    </h2>
                    <p class="leading-relaxed text-base">
                        <b
                            >Violation(s): <u>{{ data.violation }}</u></b
                        >
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import { defineComponent, onMounted, ref } from "vue";
export default defineComponent({
    setup() {
        
        const data_result = ref([]);
        const param_month = ref("");
        const param_year = ref("");
        const monthly_arr = ref([]);
        const yearly_arr = ref([]);
        const getDataSelected = async () => {
            axios
                .get("getMonthYearAndDefaults")
                .then(({ data, status }) => {
                    const { monthly, yearly, month_td, year_td } = data;
                    monthly_arr.value = monthly;
                    yearly_arr.value = yearly;
                    param_month.value = month_td;
                    param_year.value = year_td;
                    setTimeout(() => {
                        getDailyViolations();
                    }, 300);
                })
                .catch((error) => {});
        };
        const getDailyViolations = async () => {
            axios
                .post("getDailyViolations", {
                    month: param_month.value,
                    year: param_year.value,
                })
                .then(({ data, status }) => {
                    data_result.value = data;
                })
                .catch((error) => {});
        };
        onMounted(() => {
             getDataSelected()
        });
        return {
            getDailyViolations,
            getDataSelected,
            param_month,
            param_year,
            monthly_arr,
            yearly_arr,
            data_result,
        };
    },
});
</script>
