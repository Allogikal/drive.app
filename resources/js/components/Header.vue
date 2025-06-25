<script setup>
// IMPORT LOCALE IMAGES
import Placeholder from '@/assets/images/40x40.svg'
import Logo from '@/assets/images/logo.png'
import { computed, ref, onMounted, watchEffect } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import gsap from 'gsap';

const burgerOpen = ref(false);
const isOpen = ref(false);

const toggleMenu = () => {
    if (!burgerOpen.value) return;
    isOpen.value = !isOpen.value;

    if (isOpen.value) {
        openMenu();
    } else {
        closeMenu();
    }
};

const openMenu = () => {
    if (!burgerOpen.value) return;
    gsap.to(burgerOpen.value, { x: 0, duration: 0.5, ease: 'power3.inOut' });
};

const closeMenu = () => {
    if (!burgerOpen.value) return;
    gsap.to(burgerOpen.value, { x: '-100%', duration: 0.5, ease: 'power3.inOut' });
};

onMounted(() => {
    if (burgerOpen.value) {
        gsap.set(burgerOpen.value, { x: '100%' });
    }
});

watchEffect(() => {
    if (window.innerWidth > 1024 && isOpen.value) {
        closeMenu();
        isOpen.value = false;
    }
});

onMounted(() => {
    if (burgerOpen.value) {
        gsap.set(burgerOpen.value, { x: '-100%' })
    }
})

const user = computed(() => usePage().props.auth.user)
const searchQuery = ref('')
const handleSearch = () => {
    router.get(route('places'), {
        search: searchQuery.value
    })
}
</script>

<template>
    <div class="burger-open" ref="burgerOpen">
        <span @click="toggleMenu">+</span>
        <ul id="mobile-menu">
            <li><a :href="route('gallery')" @click="closeMenu">Галерея</a></li>
            <li><a :href="route('map')" @click="closeMenu">Карта</a></li>
            <li><a :href="route('places')" @click="closeMenu">Страница мест</a></li>
            <li><a :href="route('profile')" @click="closeMenu">Профиль</a></li>
        </ul>
    </div>

    <header class="header--b">
        <div class="header--b__wrapper">
            <button @click="toggleMenu" class="hamburger" aria-label="Меню">
                <span class="hamburger__line"></span>
                <span class="hamburger__line"></span>
                <span class="hamburger__line"></span>
            </button>

            <div class="mobile-brand">
                <a :href="route('home')" class="logo">
                    <img :src="Logo" alt="Drive Logo">
                </a>
                <a v-if="user" :href="route('profile')" class="user">
                    <img :src="user ? user?.src : Placeholder" alt="User Avatar">
                </a>
            </div>

            <!-- Desktop navigation (hidden on mobile) -->
            <div class="header--b__wrapper-nav">
                <a :href="route('home')" class="logo--desktop">
                    <img :src="Logo" alt="Drive Logo">
                </a>
                <nav>
                    <ul>
                        <li>
                            <a :href="route('gallery')">Галерея</a>
                        </li>
                        <li>
                            <a :href="route('map')">Карта</a>
                        </li>
                        <li>
                            <a :href="route('places')">Страница мест</a>
                        </li>
                    </ul>
                </nav>
                <a v-if="user" :href="route('profile')" class="user--desktop">
                    <img :src="user ? user?.src : Placeholder" alt="User Avatar">
                </a>
            </div>

            <input @keyup.enter="handleSearch" v-model="searchQuery" v-if="$page.props.currentRoute === 'places'"
                type=" search" placeholder="Введи запрос и нажми Enter">
        </div>
    </header>
</template>