<script setup>
import { defineProps, ref, watch } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import { MagnifyingGlassIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    packages: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");

const goToPage = (url) => {
    if (url) {
        router.get(url, { search: search.value }, { preserveState: true, replace: true });
    }
};

const goToPackage = (id) => {
    router.get(route('packages.show', id));
};

watch(search, (value) => {
    router.get("/packages", { search: value }, { preserveState: true, replace: true });
});
</script>

<template>
    <AppLayout title="Packages">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Packages
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-lg p-6">

                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold">Packages List</h1>

                        <div class="relative">
                            <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                            <input 
                                v-model="search"
                                type="text" 
                                placeholder="Search"
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-300 text-gray-800 w-72"
                            />
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full rounded-lg shadow-sm">
                            <thead class="bg-gray-200 text-gray-700 uppercase text-sm tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Tracking Number</th>
                                    <th class="px-6 py-3 text-left">Origin Terminal</th>
                                    <th class="px-6 py-3 text-left">Destination Terminal</th>
                                    <th class="px-6 py-3 text-left">Last Scanned</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300 bg-white">
                                <template v-if="packages.data.length > 0">
                                    <tr v-for="pkg in packages.data" :key="pkg.id" class="hover:bg-gray-100 transition cursor-pointer"
                                        @click="goToPackage(pkg.id)">
                                        <td class="px-6 py-4 text-blue-600 font-semibold underline">{{ pkg.tracking_number }}</td>
                                        <td class="px-6 py-4 text-gray-900">{{ pkg.origin_terminal }}</td>
                                        <td class="px-6 py-4 text-gray-900">{{ pkg.destination_terminal }}</td>
                                        <td class="px-6 py-4 text-gray-900">{{ pkg.last_scanned_details  }}</td>
                                        <td :class="`px-6 py-4 ${pkg.status_color}`">{{ pkg.status }}</td>
                                        <td class="px-6 py-4 text-gray-700">
                                            <button @click.stop="goToPackage(pkg.id)" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No packages available.
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
