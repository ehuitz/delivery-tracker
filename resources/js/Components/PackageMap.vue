<script setup>
import { ref, defineProps, watch, onMounted } from "vue";
import { LMap, LTileLayer, LMarker, LPopup, LPolyline } from "@vue-leaflet/vue-leaflet";
import "leaflet/dist/leaflet.css";

const props = defineProps({
    scanHistory: {
        type: Array,
        required: true
    }
});

const zoom = ref(5);
const center = ref([59.25, 15.22]);
const routeCoordinates = ref([]);

const updateRoute = () => {
    if (props.scanHistory.length > 0) {
        center.value = [props.scanHistory[0].latitude, props.scanHistory[0].longitude];
        routeCoordinates.value = props.scanHistory.map(scan => [scan.latitude, scan.longitude]);
    }
};

onMounted(() => {
    updateRoute();
});

watch(() => props.scanHistory, () => {
    updateRoute();
}, { deep: true });

</script>

<template>
    <div class="map-wrapper">
        <LMap v-model:zoom="zoom" :center="center">
            <LTileLayer
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                :attribution="'&copy; OpenStreetMap contributors'"
            />
            <LPolyline :lat-lngs="routeCoordinates" color="blue" weight="4" />
            <LMarker v-for="(scan, index) in scanHistory" :key="index" :lat-lng="[scan.latitude, scan.longitude]">
                <LPopup>
                    <strong>{{ index === 0 ? "Home" : `Stop ${index}` }} - {{ scan.terminal }}</strong><br>
                    Scanned at: {{ scan.scanned_at }}
                </LPopup>
            </LMarker>
        </LMap>
    </div>
</template>

<style>
.map-wrapper {
    flex-grow: 1;
    height: 100%;
    min-height: 500px; /* Ensure it doesn't shrink too much */
}
</style>
