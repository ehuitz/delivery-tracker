<script setup>
import { defineProps } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { HomeIcon, FlagIcon, TruckIcon, CheckCircleIcon, QrCodeIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    package: Object
});

const getIcon = (index, scan, packageData) => {
    if (index === 0) return HomeIcon;
    if (scan.terminal === packageData.destination_terminal) return FlagIcon;
    return TruckIcon;
};
</script>

<template>
    <AppLayout :title="`Package Details - ${package.tracking_number}`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Package Details</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-6">
                <div class="w-1/3 bg-white shadow-xl rounded-lg p-6 border border-gray-200">
                    <h1 class="text-2xl font-bold mb-4 text-gray-800">Package Information</h1>

                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 bg-gray-100 p-3 rounded-md">
                            <QrCodeIcon class="w-6 h-6 text-gray-500" />
                            <div>
                                <p class="text-gray-600 text-sm">Tracking Number</p>
                                <p class="text-gray-900 font-semibold">{{ package.tracking_number }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 bg-gray-100 p-3 rounded-md">
                            <HomeIcon class="w-6 h-6 text-gray-500" />
                            <div>
                                <p class="text-gray-600 text-sm">Origin</p>
                                <p class="text-gray-900 font-semibold">{{ package.origin_terminal }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 bg-gray-100 p-3 rounded-md">
                            <FlagIcon class="w-6 h-6 text-gray-500" />
                            <div>
                                <p class="text-gray-600 text-sm">Destination</p>
                                <p class="text-gray-900 font-semibold">{{ package.destination_terminal }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 bg-gray-100 p-3 rounded-md">
                            <CheckCircleIcon class="w-6 h-6 text-gray-500" />
                            <div>
                                <p class="text-gray-600 text-sm">Status</p>
                                <p :class="`text-gray-700 font-semibold ${package.status_color}`">{{ package.status }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-2/3 bg-white shadow-xl rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Package History</h2>
                    <div class="relative">
                        <ul class="border-l-4 border-gray-300 pl-5 relative">
                            <template v-if="package.scan_history.length > 0">
                                <li v-for="(scan, index) in package.scan_history" :key="index"
                                    class="mb-6 relative flex items-start space-x-4">
                                    <div
                                        class="absolute -left-[36px] w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full border border-gray-300 shadow">
                                        <component :is="getIcon(index, scan, package)"
                                            class="w-5 h-5 text-gray-500" />
                                    </div>
                                    <div class="bg-gray-100 p-4 rounded-lg shadow-md w-full">
                                        <p class="text-sm text-indigo-600 font-semibold">{{ scan.date }}</p>
                                        <p class="text-gray-900 font-semibold text-lg">{{ scan.terminal }}</p>
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
