<script setup>
import AppLayout from '@/layouts/AppLayoutHeaderMain.vue'
// IMPORT LOCALE IMAGES
import Logo from '@/assets/images/logo.png'
import MainImg from '@/assets/images/main-img.png'
import MapMain from '@/assets/images/map-main.jpg'
import ArrowLong from '@/assets/images/arrow-long.svg'
import ArrowTRAlt from '@/assets/images/arrow-t-r-alt.svg'
import TgIconA from '@/assets/images/tg-icon-a.svg'
import WhatsappIconA from '@/assets/images/whatsapp-icon-a.svg'
import VkIconA from '@/assets/images/vk-icon-a.svg'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const user = computed(() => usePage().props.auth.user)
</script>

<template>
    <AppLayout>
        <section class="main full-height">
            <div class="main__wrapper">
                <div class="main__wrapper-headers">
                    <div class="main__wrapper-headers-head">
                        <h1>Оторвись по полн<span>ой!</span></h1>
                        <p>Онлайн гид по ночной Астрахани</p>
                    </div>
                    <div class="main__wrapper-headers-desc">
                        <p>Отпускайте тормоза и погрузитесь в безудержный ритм ночной жизни Астрахани: где бары сверкают
                            яркими огнями, клубы
                            звучат под ритмичные биты, а тусовки не знают конца. Здесь, в Астрахани, каждый вечер
                            превращается в увлекательное
                            приключение, где открываются новые грани вашего сознания. Не пропустите возможность
                            погрузиться в атмосферу свободы,
                            радости и непрерывного веселья – добро пожаловать на неспящие берега тусовок и живых
                            вечеринок в самом сердце Астрахани!</p>
                    </div>
                </div>
                <div class="main__wrapper-btns">
                    <a :href="route('places')" class="btn small-btn trans">Каталог мест</a>
                    <a :href="route('register')" v-show="!user" class="btn default-btn trans">Зарегистрироваться на
                        сайте</a>
                </div>
                <div class="main__wrapper-map">
                    <div class="main__wrapper-map-content">
                        <div class="main__wrapper-map-content-img">
                            <img :src="MapMain" alt="MapMain">
                            <a :href="route('map')">
                                <p>Воспользуйся интерактивной картой лучших мест Астрахани!</p>
                                <img :src="ArrowLong" alt="ArrowLong">
                            </a>
                        </div>
                    </div>
                    <div class="main__wrapper-map-href">
                        <p>Загляните в нашу супер классную галерею по астрахани</p>
                        <a :href="route('gallery')"><img :src="ArrowTRAlt" alt="ArrowTRAlt"></a>
                    </div>
                </div>
            </div>
            <div class="main__logo">
                <div class="main__logo-wrapper">
                    <img :src="Logo" alt="logo">
                </div>
            </div>
            <div class="main__img">
                <img :src="MainImg" alt="main-img">
            </div>
            <div class="main__social">
                <div class="main__social-icons">
                    <a href="https://t.me/driveapp" target="_blank">
                        <img :src="TgIconA" alt="Telegram" />
                    </a>
                    <a href="https://wa.me/79035641128" target="_blank">
                        <img :src="WhatsappIconA" alt="WhatsApp" />
                    </a>
                    <a href="https://vk.com/drive_app" target="_blank">
                        <img :src="VkIconA" alt="VKontakte" />
                    </a>
                </div>
                <p>Присоединяйтесь к нам!</p>
            </div>
        </section>
    </AppLayout>
</template>