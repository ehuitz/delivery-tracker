<script setup>
import { defineProps } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    terminals: Array
});
</script>

<template>
    <AppLayout title="Terminals">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Terminals
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-lg p-6">
                    <h1 class="text-2xl font-bold mb-4">Terminals List</h1>
                    <div class="overflow-x-auto">
                        <table class="w-full rounded-lg shadow-sm">
                            <thead class="bg-gray-200 text-gray-700 uppercase text-sm tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Name</th>
                                    <th class="px-6 py-3 text-left">City</th>
                                    <th class="px-6 py-3 text-left">Latitude</th>
                                    <th class="px-6 py-3 text-left">Longitude</th>
                                    <th class="px-6 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300 bg-white">
                                <template v-if="terminals.length > 0">
                                    <tr v-for="terminal in terminals" :key="terminal.id"
                                        class="hover:bg-gray-100 transition">
                                        <td class="px-6 py-4 text-gray-900">{{ terminal.name }}</td>
                                        <td class="px-6 py-4 text-gray-700">{{ terminal.city }}</td>
                                        <td class="px-6 py-4 text-gray-700">{{ terminal.latitude }}</td>
                                        <td class="px-6 py-4 text-gray-700">{{ terminal.longitude }}</td>
                                        <td class="px-6 py-4 text-gray-700"></td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No terminals available.
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>