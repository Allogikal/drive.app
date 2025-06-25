<script setup>
import Swal from 'sweetalert2'
import axios from 'axios'
// IMPORT LOCALE IMAGES
import Logo from '@/assets/images/logo.png'

const logout = async () => {
    try {
        const response = await axios.post(route('logout'))
        if (response.status === 200) {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Вы вышли из системы.",
                showConfirmButton: false,
                timer: 1500
            })
            setTimeout(() => {
                window.location.href = route('login')
            }, 1600)
        }
    } catch (error) {
        console.error(error)
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Ошибка выхода. Попробуйте ещё раз.",
            showConfirmButton: false,
            timer: 1500
        })
    }
}
</script>

<template>
    <header class="header--b header--b-admin">
        <div class="header--b__wrapper">

            <!-- Desktop navigation (hidden on mobile) -->
            <div class="header--b__wrapper-nav">
                <a :href="route('admin-applications')" class="logo--desktop">
                    <img :src="Logo" alt="Drive Logo">
                </a>
                <nav>
                    <ul>
                        <li><a :href="route('admin-applications')">Заявки</a></li>
                        <li><a :href="route('admin-users')">Пользователи</a></li>
                        <li><a :href="route('admin-places')">Места</a></li>
                        <li><a :href="route('admin-reviews')">Отзывы</a></li>
                        <li><a :href="route('admin-gallery')">Галерея</a></li>
                    </ul>
                </nav>
                <a href="#" @click.prevent="logout" class="">
                    Выход
                </a>
            </div>
        </div>
    </header>
</template>