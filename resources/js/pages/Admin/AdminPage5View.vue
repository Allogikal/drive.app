<script setup>
import AppLayout from '@/layouts/AppLayoutHeaderAdmin.vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
    userImages: Array
})

const acceptImage = async (id) => {
    await router.post(route('admin.accept-user-image', id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Изображение принято!",
                showConfirmButton: false,
                timer: 1500
            });
        },
        onError: () => {
            Swal.fire({
                icon: "error",
                title: "Ошибка при принятии изображения",
                showConfirmButton: false,
                timer: 1500
            });
        }
    })
}

const rejectImage = async (id) => {
    const result = await Swal.fire({
        title: 'Вы уверены?',
        text: 'Вы хотите отклонить это изображение?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Да, отклонить!',
        cancelButtonText: 'Отменить'
    })

    if (result.isConfirmed) {
        await router.post(route('admin.reject-user-image', id), {}, {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Изображение отклонено!",
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            onError: () => {
                Swal.fire({
                    icon: "error",
                    title: "Ошибка при отклонении изображения",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
    }
}
</script>

<template>
    <AppLayout>
        <section class="admin-gallery admin-section admin-body">
            <div class="container wrapper">
                <h1 class="title">Галерея</h1>
                <div v-if="props.userImages.length === 0" class="no-images">
                    Нет новых изображений.
                </div>
                <div v-else class="admin-gallery__wrapper">
                    <div v-for="image in props.userImages" :key="image.id" class="admin-gallery__wrapper-item">
                        <div class="admin-gallery__wrapper-item-img">
                            <img :src="image.src" alt="" />
                        </div>
                        <div class="admin-gallery__wrapper-item-content">
                            <div class="admin-gallery__wrapper-item-content-headers">
                                <h2>{{ image.user.name }} {{ image.user.surname }}</h2>
                                <p>{{ image.description }}</p>
                            </div>
                            <div class="admin-gallery__wrapper-item-content-btns">
                                <button v-show="image.status === 'pending'" class="btn small-btn trans alt"
                                    @click="acceptImage(image.id)">Принять</button>
                                <button v-show="image.status === 'pending'" class="btn small-btn trans alt"
                                    @click="rejectImage(image.id)">Отклонить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>