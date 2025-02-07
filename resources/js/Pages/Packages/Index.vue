<script setup>
import { defineProps } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    packages: Object,
});

const goToPage = (url) => {
    if (url) {
        router.get(url);
    }
};
</script>

<template>
    <AppLayout title="Packages">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Packages
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-lg p-6">
                    <h1 class="text-2xl font-bold mb-4">Packages List</h1>
                    <div class="overflow-x-auto">
                        <table class="w-full rounded-lg shadow-sm">
                            <thead class="bg-gray-200 text-gray-700 uppercase text-sm tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Tracking Number</th>
                                    <th class="px-6 py-3 text-left">Origin Terminal</th>
                                    <th class="px-6 py-3 text-left">Destination Terminal</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300 bg-white">
                                <template v-if="packages.data.length > 0">
                                    <tr v-for="pkg in packages.data" :key="pkg.id" class="hover:bg-gray-100 transition">
                                        <td class="px-6 py-4 text-gray-900">{{ pkg.tracking_number }}</td>
                                        <td class="px-6 py-4 text-gray-900">{{ pkg.origin_terminal }}</td>
                                        <td class="px-6 py-4 text-gray-900">{{ pkg.destination_terminal }}</td>
                                        <td :class="`px-6 py-4 ${pkg.status_color}`">{{ pkg.status }}</td>
                                        <td class="px-6 py-4 text-gray-700"></td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No packages available.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-between items-center">
                        <button v-if="packages.prev_page_url" @click="goToPage(packages.prev_page_url)"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Previous
                        </button>

                        <span class="text-gray-600">Page {{ packages.current_page }} of {{ packages.last_page }}</span>

                        <button v-if="packages.next_page_url" @click="goToPage(packages.next_page_url)"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>