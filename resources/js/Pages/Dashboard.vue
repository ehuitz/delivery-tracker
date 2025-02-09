<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Delivery Tracker</h1>
                    
                    <p class="text-gray-700 leading-relaxed">
                        Keep track of your shipments in real-time. Our system provides instant updates, a detailed scan history, and a map-based journey tracker to help you stay informed at every step.
                    </p>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center space-x-3">
                            <span class="text-indigo-600 text-2xl font-bold">🚀</span>
                            <div>
                                <p class="text-gray-900 font-semibold">Built with Laravel 11</p>
                                <p class="text-gray-600 text-sm">Leveraging the latest version for performance and security.</p>
                            </div>
                        </div>

                        <div class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center space-x-3">
                            <span class="text-green-600 text-2xl font-bold">🔍</span>
                            <div>
                                <p class="text-gray-900 font-semibold">MeiliSearch-Powered</p>
                                <p class="text-gray-600 text-sm">Lightning-fast search for instant package lookups.</p>
                            </div>
                        </div>

                        <div class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center space-x-3">
                            <span class="text-blue-600 text-2xl font-bold">🐘</span>
                            <div>
                                <p class="text-gray-900 font-semibold">PostgreSQL Database</p>
                                <p class="text-gray-600 text-sm">Ensuring scalability and reliability for your data.</p>
                            </div>
                        </div>

                        <div class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center space-x-3">
                            <span class="text-yellow-600 text-2xl font-bold">📡</span>
                            <div>
                                <p class="text-gray-900 font-semibold">Live Updates with Soketi</p>
                                <p class="text-gray-600 text-sm">Real-time WebSockets for seamless tracking updates.</p>
                            </div>
                        </div>

                        <div class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center space-x-3">
                            <span class="text-red-600 text-2xl font-bold">⚡</span>
                            <div>
                                <p class="text-gray-900 font-semibold">Queue-Powered Processing</p>
                                <p class="text-gray-600 text-sm">Handling high-volume updates with Redis queues. (not implemented yet)</p>
                            </div>
                        </div>

                        <div class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center space-x-3">
                            <span class="text-purple-600 text-2xl font-bold">🌎</span>
                            <div>
                                <p class="text-gray-900 font-semibold">Interactive Map</p>
                                <p class="text-gray-600 text-sm">Track package locations with Leaflet-based mapping.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
