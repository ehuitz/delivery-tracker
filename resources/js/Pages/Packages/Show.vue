<script setup>
import { defineProps, ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import { HomeIcon, FlagIcon, TruckIcon, CheckCircleIcon, QrCodeIcon } from "@heroicons/vue/24/outline";
import Echo from "laravel-echo";
import PackageMap from "@/Components/PackageMap.vue";

const props = defineProps({
    package: Object,
    terminals: Array,
});

const packageData = ref({ ...props.package });
const terminal_id = ref("");
const scanned_at = ref(new Date().toISOString().slice(0, 16));
const errors = ref({});

const submitScan = () => {
    router.post(`/api/packages/${packageData.value.id}/scans`, {
        terminal_id: terminal_id.value,
        scanned_at: scanned_at.value,
    }, {
        preserveState: true,
        onError: (err) => errors.value = err,
        onSuccess: () => {
            terminal_id.value = "";
            scanned_at.value = new Date().toISOString().slice(0, 16);
            errors.value = {};
        }
    });
};

const getIcon = (index, scan) => {
    if (index === 0) return HomeIcon;
    if (scan.terminal === packageData.value.destination_terminal) return FlagIcon;
    return TruckIcon;
};

onMounted(() => {
    window.Echo.private(`package.${packageData.value.id}`)
        .listen(".package.scanned", (event) => {
            packageData.value = { ...packageData.value, ...event };
        });
});
</script>

<template>
    <AppLayout :title="`Package Details - ${packageData.tracking_number}`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Package Details</h2>
        </template>

        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
                <div class="flex flex-col lg:flex-row gap-6">
                    <div class="lg:w-1/3 bg-white shadow-xl rounded-lg p-6 border border-gray-200">
                        <h1 class="text-2xl font-bold mb-4 text-gray-800">Package Information</h1>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 bg-gray-100 p-3 rounded-md">
                                <QrCodeIcon class="w-6 h-6 text-gray-500" />
                                <div>
                                    <p class="text-gray-600 text-sm">Tracking Number</p>
                                    <p class="text-gray-900 font-semibold">{{ packageData.tracking_number }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 bg-gray-100 p-3 rounded-md">
                                <HomeIcon class="w-6 h-6 text-gray-500" />
                                <div>
                                    <p class="text-gray-600 text-sm">Origin</p>
                                    <p class="text-gray-900 font-semibold">{{ packageData.origin_terminal }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 bg-gray-100 p-3 rounded-md">
                                <FlagIcon class="w-6 h-6 text-gray-500" />
                                <div>
                                    <p class="text-gray-600 text-sm">Destination</p>
                                    <p class="text-gray-900 font-semibold">{{ packageData.destination_terminal }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 bg-gray-100 p-3 rounded-md">
                                <CheckCircleIcon class="w-6 h-6 text-gray-500" />
                                <div>
                                    <p class="text-gray-600 text-sm">Status</p>
                                    <p :class="`text-gray-700 font-semibold ${packageData.status_color}`">{{
                                        packageData.status
                                    }}</p>
                                </div>
                            </div>
                        </div>
                        <h2 class="mt-6 text-lg font-semibold text-gray-800">Log a Scan</h2>
                        <div class="mt-2 space-y-2">
                            <select v-model="terminal_id" class="w-full p-2 border rounded-md">
                                <option value="" disabled>Select Terminal</option>
                                <option v-for="term in terminals" :key="term.id" :value="term.id">
                                    {{ term.name }} - {{ term.city }}
                                </option>
                            </select>
                            <input v-model="scanned_at" type="datetime-local" class="w-full p-2 border rounded-md" />
                            <button @click="submitScan"
                                class="w-full bg-indigo-600 text-white p-2 rounded-md hover:bg-indigo-700">
                                Add Scan
                            </button>
                            <p v-if="errors.terminal_id" class="text-red-500 text-sm">{{ errors.terminal_id[0] }}</p>
                            <p v-if="errors.scanned_at" class="text-red-500 text-sm">{{ errors.scanned_at[0] }}</p>
                        </div>
                    </div>

                    <div class="lg:w-2/3 bg-white shadow-xl rounded-lg p-6 flex flex-col">
                        <h2 class="text-xl font-bold mb-4">Package Route Map</h2>
                        <div class="flex-grow">
                            <PackageMap :scanHistory="packageData.scan_history" />
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-xl rounded-lg p-6 w-full">
                    <h2 class="text-xl font-bold mb-4">Package History</h2>
                    <div class="relative max-h-[420px] overflow-y-auto">
                        <div class="absolute left-3 top-0 bottom-0 w-1 bg-gray-300"
                            :style="{ height: packageData.scan_history.length * 100 + 'px' }">
                        </div>
                        <ul class="relative pl-4">
                            <template v-if="packageData.scan_history.length > 0">
                                <li v-for="(scan, index) in packageData.scan_history" :key="index"
                                    class="relative mb-6 flex items-start space-x-4">
                                    <div
                                        class="absolute left-[-16px] w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full border border-gray-300 shadow">
                                        <component :is="getIcon(index, scan)" class="w-5 h-5 text-gray-500" />
                                    </div>
                                    <div class="bg-gray-100 p-4 rounded-lg shadow-md w-full">
                                        <p class="text-sm text-indigo-600 font-semibold">{{ scan.terminal }}</p>
                                        <p class="text-gray-600 text-sm">{{ scan.scanned_at }}</p>
                                    </div>
                                </li>
                            </template>
                            <p v-else class="text-gray-500">No scan history available.</p>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </AppLayout>
</template>
