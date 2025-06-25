<script setup>
const props = defineProps({
    place: Object
})

const emit = defineEmits(['close'])
const handleClose = () => {
    emit('close')
}
</script>

<template>
    <section class="city">
        <span @click.prevent="handleClose">+</span>
        <div class="city__wrapper">
            <div class="city__wrapper-content bar-content">
                <h1>{{ place?.title }}</h1>
                <div class="city__wrapper-content-desc">
                    <p>{{ place?.description }}</p>
                    <div>
                        <img :src="place?.images[0]?.src" alt="image">
                        <img :src="place?.images[1]?.src" alt="image">
                    </div>
                </div>
            </div>
            <div class="city__wrapper-imgs">
                <h2>Фотографии</h2>
                <div v-for="image in place.images" :key="image.id">
                    <img :src="image?.src" alt="image">
                </div>
            </div>
        </div>
    </section>
</template>