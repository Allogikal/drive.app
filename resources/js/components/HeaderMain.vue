<script setup>
import Logo from '@/assets/images/logo.png'
</script>

<template>
    <header class="header-main">
        <nav class="header__wrapper container">
            <ul>
                <li>
                    <a :href="route('gallery')" class="default-anim-a trans">Галерея</a>
                </li>
                <li>
                    <a :href="route('map')" class="default-anim-a trans">Карта</a>
                </li>
                <li>
                    <a :href="route('home')"><img :src="Logo" alt="logo"></a>
                </li>
                <li>
                    <a :href="route('places')" class="default-anim-a trans">Страница мест</a>
                </li>
            </ul>
        </nav>
    </header>
</template>