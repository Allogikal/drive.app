<script setup>
// IMAGES IMPORTS
import ArrowTR from '@/assets/images/arrow-t-r.svg';
import ProfileShopTest from '@/assets/images/profile-shop-test.jpg';

const props = defineProps({
    places: Array
});
</script>

<template>
    <section class="city">
        <h1>Каталог</h1>
        <div class="bars">
            <div class="bars__wrapper">
                <div v-for="place in places" :key="place.id">
                    <div v-if="place.status !== 'inactive'" class="bars__wrapper-item">
                        <div class="bars__wrapper-item-img">
                            <img :src="place?.images[0]?.src ? place?.images[0]?.src : ProfileShopTest" alt="image">
                        </div>
                        <div class="bars__wrapper-item-content">
                            <h2>{{ place?.title }}</h2>
                            <p>{{ place?.address }}</p>
                            <p>Время работы: {{ place?.worktime_start }}-{{ place?.worktime_end }}
                            </p>
                            <p>Средний чек: {{ place?.average_price }}</p>
                        </div>
                        <span>{{ place.rating }}</span>
                        <a :href="route('place-profile', place.id)">
                            <img :src="ArrowTR" alt="arrow-t-r">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>