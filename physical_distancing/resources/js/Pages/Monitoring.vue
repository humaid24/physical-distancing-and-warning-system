<template>
    <Head title="Monitoring" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Monitoring
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-8 bg-white border-b border-gray-200">
                        <h1 class="font-extrabold">Hi, {{ auth?.user?.name.toUpperCase() }} :) </h1>
                        Do you want to open {{ appName }}? Just press the button below. 
                    </div>
                </div>
            </div>
            <div class="mt-6 max-w-2xl mx-auto sm:px36 lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-3 lg:gap-x-8">
                <a type="button" :href="system_link+'?mode=live'" target="_blank" class="bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-4 rounded" :class="{ 'opacity-25': processingLiveMon }" :disabled="processingLiveMon" v-on:click="open_live_monitoring_window">{{processingLiveMon ? 'Loading...' : 'Start Monitoring Live Video'}}</a>
                
            </div>
        </div>
        <div class="container mx-auto sm:px-6 lg:px-8 grid grid-flow-col grid-cols-3 gap-4 pb-16">
        <div class="col-span-2 bg-white shadow-sm rounded-lg p-4 max-h-80 overflow-y-scroll" v-if="(typeof view_recorded_files == 'boolean') ? view_recorded_files : false ">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 ">
                <thead class="text-xs text-gray-800 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-2">
                            No.
                        </th>
                        <th scope="col" class="px-6 py-3">
                            File Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                        v-for="(data, i) in fileList"
                        :key="i"
                    >
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            {{ i + 1 }}
                        </th>
                        <td class="px-6 py-4">
                            {{ data.fileName }}
                        </td>
                        <td class="px-6 py-4">
                            <a type="button" :href="system_link+`?mode=recorded&fileLoc=${data.fileLoc+'\\'+data.fileName}`" target="_blank" class="bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-4 rounded">View Recorded Video</a>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        
        <div class="col-span-1 bg-white shadow-sm rounded-lg p-4">
         <div v-if="success != ''" class="mb-4 font-medium text-sm text-green-600">
            {{ success }}
        </div>
                <form @submit.prevent="formSubmit" enctype="multipart/form-data">
                    <div class="form-group mb-6">
                    <label for="fileName" class="form-label inline-block mb-2 text-gray-700">File Name</label>
                    <input type="text" class="form-control
                        block
                        w-full
                        px-3
                        py-1.5
                        text-base
                        font-normal
                        text-gray-700
                        bg-white bg-clip-padding
                        border border-solid border-gray-300
                        rounded
                        transition
                        ease-in-out
                        m-0
                        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" 
                        id="fileName"
                        v-model="fileName"
                        placeholder="File Name">
                    </div>
                    <div class="form-group mb-6">
                    <label for="fileUpload" class="form-label inline-block mb-2 text-gray-700">File Upload <small>(MP4)</small></label>
                    <input type="file" class="form-control block
                        w-full
                        px-3
                        py-1.5
                        text-base
                        font-normal
                        text-gray-700
                        bg-white bg-clip-padding
                        border border-solid border-gray-300
                        rounded
                        transition
                        ease-in-out
                        m-0
                        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" 
                        id="fileUpload"
                        ref="fileUpload"
                        v-on:change="onFileChange"
                        placeholder="Select File Upload">
                    </div>
                    <button type="submit" class="
                    w-full
                    px-6
                    py-2.5
                    bg-blue-600
                    text-white
                    font-medium
                    text-xs
                    leading-tight
                    uppercase
                    rounded
                    shadow-md
                    hover:bg-blue-700 hover:shadow-lg
                    focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0
                    active:bg-blue-800 active:shadow-lg
                    transition
                    duration-150
                    ease-in-out"
                    
                     :class="{ 'opacity-25': ((fileName == '' || file == '') ? true : false) }"
                    :disabled="(fileName == '' || file == '')"
                    >{{loading ?'Loading...' : 'Submit'}}</button>
                    
                </form>
        </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import { ref } from 'vue'
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import http from '../utils/https';
import axios from "axios";
export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
    },
    props:['appName', 'auth', 'system_link', 'view_recorded_files'],
    data() {
        return {
          fileName: '',
          file: '',
          success: '',
          loading: false,
          fileList: []
        };
    },
    methods: {
        onFileChange(e){
            this.file = e.target.files[0];
        },
        clearData() {
            this.fileName = '';
            this.file = '';
            this.success = '';
            this.$refs.fileUpload.value = null;
            this.loading = false;
        },
        getFilesList(){
            http.get('getFilesList')
            .then(({data, status}) => {
                this.fileList = data;
            });
        },
        formSubmit(e) {
            this.loading = true;
            var allowedExtensions  = ['mp4'];
            if(allowedExtensions.includes((this.file?.name).split('.').pop())){
                 let currentObj = this;
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
                let formData = new FormData();
                formData.append('file', this.file);
                formData.append('fileName', this.fileName);

                axios.post('formSubmit', formData, config)
                   .then(({ data, status }) =>  {
                       currentObj.success = data?.success;
                        this.getFilesList();
                       setTimeout(() => {
                        this.clearData();
                        
                        if(((typeof this.view_recorded_files == 'boolean') ? this.view_recorded_files : false) == false){
                            window.open(this.system_link+`?mode=recorded&fileLoc=${data?.fileUploaded}`, '_blank');
                        }
                       }, 2500)
                   })
                   .catch(function (error) {
                    currentObj.output = error;
                    this.loading = false
                    this.clearData();
                });
            }else{
                this.loading = false;
                this.clearData();
            }
        }
    },
    mounted(){
        this.getFilesList();
    }
}
</script>
