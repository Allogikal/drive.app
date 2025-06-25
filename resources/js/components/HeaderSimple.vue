<script setup>
// IMPORT LOCALE IMAGES
import Logo from '@/assets/images/logo.png'

const goBack = () => {
    window.history.back()
}
</script>

<template>
    <header class="simple-header">
        <a :href="route('home')"><img :src="Logo" alt="logo"></a>
        <a href="#" @click.prevent="goBack">Назад</a>
    </header>
</template>