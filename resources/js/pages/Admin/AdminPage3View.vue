<script setup>
import AppLayout from '@/layouts/AppLayoutHeaderAdmin.vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import ArrowTR from '@/assets/images/arrow-t-r.svg'

const props = defineProps({
    restaurants: Array
})

const deleteRestaurant = async (id) => {
    const result = await Swal.fire({
        title: "Вы уверены?",
        text: "Это действие нельзя отменить!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#888",
        confirmButtonText: "Да, удалить!"
    })

    if (result.isConfirmed) {
        try {
            await router.delete(route('restaurants.destroy', id), {
                preserveScroll: true
            })
        } catch (error) {
            console.error(error)
            Swal.fire({
                icon: "error",
                title: "Ошибка при удалении заведения"
            })
        }
    }
}
</script>

<template>
    <AppLayout>
        <section class="admin-places admin-section admin-body">
            <div class="container wrapper">
                <h1 class="title">Места</h1>
                <div v-if="props.restaurants.length === 0" class="no-places">
                    Нет зарегистрированных заведений.
                </div>
                <div v-else class="admin-places__wrapper">
                    <div v-for="place in props.restaurants" :key="place.id" class="admin-places__wrapper-item">
                        <div class="admin-places__wrapper-item-block">
                            <div class="admin-places__wrapper-item-block-img">
                                <img :src="place.images[0].src" alt="Фото заведения" />
                            </div>
                            <div class="admin-places__wrapper-item-block-content wrapper">
                                <h2>{{ place.title }}</h2>
                                <p>{{ place.description }}</p>
                                <ul>
                                    <li>Адрес: {{ place.address }}</li>
                                    <li>Средний чек: {{ place.average_price }}</li>
                                    <li>Отзывы: {{ place.reviews.length }}</li>
                                </ul>
                            </div>
                            <div class="admin-places__wrapper-item-block-rate">
                                <p>{{ place.rating.toFixed(2) }}</p>
                            </div>
                            <a :href="route('place-profile', place.id)" class="admin-places__wrapper-item-block-check">
                                <img :src="ArrowTR" alt="ArrowTR" />
                            </a>
                        </div>
                        <button @click="deleteRestaurant(place.id)" class="btn small-btn trans alt">
                            Удалить
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>