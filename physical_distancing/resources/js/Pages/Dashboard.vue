<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboards
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="font-extrabold">
                            Hi, {{ auth.user.name.toUpperCase() }} :)
                        </h1>
                        Welcome to {{ appName }}!
                    </div>
                </div>
            </div>
            <section class="text-gray-900 body-font">
                <div
                    class="
                        container
                        py-6
                        mx-auto
                        flex
                        sm:px-6
                        items-center
                        md:flex-row
                        flex-col
                        max-w-7xl
                    "
                >
                    <div
                        class="
                            flex flex-col
                            md:pr-10 md:mb-0
                            mb-6
                            pr-0
                            w-full
                            md:w-auto md:text-left
                            text-center
                        "
                    >
                        <h2
                            class="
                                text-xs text-indigo-500
                                tracking-widest
                                font-medium
                                title-font
                                mb-1
                            "
                        >
                            Physical Distancing Monitoring System
                        </h2>
                        <h1
                            class="
                                md:text-3xl
                                text-2xl
                                font-medium
                                title-font
                                text-gray-900
                            "
                        >
                            Physical Distancing Violation Graph
                        </h1>
                    </div>
                    <div
                        class="
                            flex
                            lg:ml-auto
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
                                block
                                w-full
                                border border-gray-300
                                bg-white
                                rounded-md
                                shadow-sm
                                focus:outline-none
                                focus:ring-indigo-500
                                focus:border-indigo-500
                                sm:text-sm
                            "
                            v-model="param_selector"
                            v-on:change="getDataSelected"
                        >
                            <option
                                v-for="(data, i) in selector"
                                :key="i"
                                :value="i"
                            >
                                {{ data }}
                            </option>
                        </select>
                    </div>
                </div>
            </section>

            <div
                class="max-w-7xl mx-auto sm:px-6 lg:px-4"
                v-if="param_selector != 2"
            >
                <MonthlyChart :param_selector="param_selector" />
            </div>
            <DialyViolationRecord v-else />
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import { ref, onMounted } from "vue";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import MonthlyChart from "./MonthlyChart.vue";
import DialyViolationRecord from "./DailyViolationRecord.vue";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        MonthlyChart,
        DialyViolationRecord,
    },
    props: ["appName", "auth"],
    setup() {
        const param_selector = ref(0);
        const selector = ref([
            "Sort violations within 7 days",
            "Sort violations by month",
            "Sort daily violations by month",
        ]);

        // onMounted(getDataSelected);
        return {
            selector,
            param_selector,
        };
    },
};
</script>
