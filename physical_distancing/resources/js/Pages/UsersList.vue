<template>
    <Head title="Users List" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users List
            </h2>
        </template>

        <div class="py-12 px-24">
            
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-900">
           
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                </div>
                <input type="text" id="table-search-users" v-model="searchText" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for users name or email">
            </div>
        </div>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Username
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Position
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                     v-for="(data, i) in data_result?.data"
                    :key="i"
                >
                    <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        {{ data?.name }}
                    </th>
                    <td class="px-6 py-4">
                         {{ data?.email }}
                    </td>
                    <td class="px-6 py-4">
                         {{ data?.username }}
                    </td>
                    <td class="px-6 py-4">
                        {{ data?.isSupperAdmin == "Y" ? "Super Admin" : "System User" }}
                    </td>
                    <td class="px-6 py-4">
                        {{ data?.isApprovedByAdmin == "Y" ? "Approved" : "Request fo Approval"}}
                    </td>
                     <td class="px-6 py-4">
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-4 rounded" :class="{ 'opacity-25': (data?.isApprovedByAdmin == 'Y' ? true : false) }" :disabled="data?.isApprovedByAdmin == 'Y' ? true : false" v-on:click="approve_user(data.id)">Approved</button>
                    </td>
                </tr>
                
            </tbody>
        </table>
        
        <div class="my-4 flex justify-center">
            <div class="flex flex-col items-center">
            <!-- Help text -->
            <span class="text-sm text-gray-700 dark:text-gray-400">
                Showing <span class="font-semibold text-gray-900 dark:text-white">{{ data_result?.from }}</span> to <span class="font-semibold text-gray-900 dark:text-white">{{ data_result?.to }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ data_result?.total }}</span> Entries
            </span>
            <!-- Buttons -->
            <div class="inline-flex mt-2 xs:mt-0">
                <button 
                class="px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-l hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                :class="{ 'opacity-25': (data_result?.current_page == 1 ? true : false) }"
                :disabled="data_result?.current_page == 1 ? true : false"
                v-on:click="getUsersList(searchText, (data_result?.current_page > 1 ? (data_result?.current_page - 1) : 1 ) )"
                >
                    Prev
                </button>
                <button 
                    class="px-4 py-2 text-sm font-medium text-white bg-gray-800 border-0 border-l border-gray-700 rounded-r hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    :class="{ 'opacity-25': (data_result?.current_page == data_result?.last_page ? true : false) }"
                    :disabled="data_result?.current_page == data_result?.last_page ? true : false"
                    v-on:click="getUsersList(searchText, (data_result?.current_page == data_result?.last_page ? data_result?.last_page : (data_result?.current_page < data_result?.last_page ? data_result?.current_page + 1 : data_result?.last_page ) ) )"
                >
                    Next
                </button>
            </div>
            </div>
        </div>
    </div>

        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";

import { defineComponent, onMounted, ref } from "vue";

import _ from "lodash";
export default defineComponent({
    components: {
        BreezeAuthenticatedLayout,
        Head,
    },
    props: ["appName", "auth"],
    data() {
        return {
            searchText: ""
        }
    },
    watch: {
        searchText: _.debounce(function (data) {
           this.getUsersList(data);
        }),
    },
    setup() {
        const data_result = ref([]);
        
          const getUsersList = async (data = '', page = 1) => {
             axios
                .get(`get-users?page=${page}&searchText=${data}`)
                .then(({ data, status }) => {
                   data_result.value = data;
                })
                .catch((error) => {});
            }
        
            

         const approve_user = async (id) => {
            axios.post(`approve-user`, {id}).then(({ data, status }) => {
                   console.log("data", data);
                    getUsersList();
                }).catch((error) => {});
         }

        onMounted(() => {
            getUsersList();
        });
        return {
            data_result,
            getUsersList,
            approve_user
        };
    },
});
</script>
