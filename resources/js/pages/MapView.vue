<script setup>
import { ref, onMounted, watch } from 'vue'
import AppLayout from '@/layouts/AppLayoutHeader.vue'
// IMPORT COMPONENTS
import MapBarComponent from '@/components/MapBarComponent.vue';
import MapCityComponent from '@/components/MapCityComponent.vue';
import MapComponent from '@/components/MapComponent.vue';
// IMPORTS IMAGES
import Point from '@/assets/images/point.svg'

const isMapVisible = ref(false)
const toggleMap = () => {
    isMapVisible.value = !isMapVisible.value
}
const props = defineProps({
    places: Array
})
const selectedPlace = ref(null)
const selectedCity = ref(false)
const showPlaceInfo = (place) => {
    selectedPlace.value = place
}
const toggleCityInfo = () => {
    selectedCity.value = !selectedCity.value
}
const closePopup = () => {
    selectedPlace.value = null
}
</script>

<template>
    <AppLayout>
        <main>
            <button @click.prevent="toggleMap" class="open-bars">{{ isMapVisible ? 'Свернуть' : 'Развернуть' }}
                каталог</button>

            <section class="map">
                <div v-for="place in places" :key="place.id" class="map-marker" :style="{
                    left: place.longitude + '%',
                    top: place.latitude + '%',
                }" @click="showPlaceInfo(place)">
                    <div class="marker-icon">
                        <img :src="Point" alt="Point">
                    </div>
                </div>
                <div class="map-marker-city" @click="toggleCityInfo()">
                    <div class="marker-icon-city">
                        <img :src="Point" alt="Point">
                    </div>
                </div>
            </section>

            <MapComponent :places="places" v-show="isMapVisible" />
            <MapCityComponent v-if="selectedCity" @close="toggleCityInfo" />
            <MapBarComponent v-if="selectedPlace" :place="selectedPlace" @close="closePopup" />
        </main>
    </AppLayout>
</template>

<style scoped>
.map-container {
    position: relative;
    width: 100%;
    max-width: 1000px;
    margin: auto;
    height: 500px;
    overflow: hidden;
    border-radius: 12px;
}

.map-background {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.map-marker,
.map-marker-city {
    position: absolute;
    transform: translate(-50%, -50%);
    cursor: pointer;
    transition: opacity 0.3s ease;
}

.map-marker-city {
    top: 80%;
    left: 55%;
}

.marker-icon,
.marker-icon-city {
    width: 28px;
    height: 26px;
}
</style>