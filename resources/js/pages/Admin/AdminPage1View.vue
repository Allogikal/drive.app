<script setup>
import AppLayout from '@/layouts/AppLayoutHeaderAdmin.vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
    applications: Array
})

const approveApplication = async (id) => {
    router.post(route('admin.approve-application', id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Заведение активировано!",
                showConfirmButton: false,
                timer: 1500
            });
        },
        onError: () => {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Ошибка при отклонении",
                showConfirmButton: false,
                timer: 1500
            });
        }
    })
}

const rejectApplication = async (id) => {
    const result = await Swal.fire({
        title: 'Вы уверены?',
        text: "Это действие нельзя отменить!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Отмена'
    })
    if (result.isConfirmed) {
        router.post(route('admin.reject-application', id), {}, {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Заявка отклонена!",
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            onError: () => {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Ошибка при отклонении",
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
        <section class="admin-app admin-section admin-body">
            <div class="container wrapper">
                <h1 class="title">Заявки</h1>
                <div v-if="props.applications.length === 0" class="no-applications">
                    Нет новых заявок.
                </div>
                <div v-else class="admin-app__wrapper">
                    <div v-for="application in props.applications" :key="application.id"
                        class="admin-app__wrapper-item wrapper">
                        <div class="admin-app__wrapper-item-block">
                            <h2>{{ application.user.name }} {{ application.user.surname }}</h2>
                            <div>
                                <p>{{ application.restaurant_mail }}</p>
                                <p>{{ application.phone }}</p>
                                <p>{{ application.address }}</p>
                            </div>
                        </div>
                        <div class="admin-app__wrapper-item-block">
                            <h2>{{ application.title }}</h2>
                            <div>
                                <p>ИНН: {{ application.INN }}</p>
                                <p>КПП: {{ application.KPP ?? '-' }}</p>
                                <p>ОГРН: {{ application.OGRN }}</p>
                            </div>
                        </div>
                        <div class="admin-app__wrapper-item-block">
                            <div>
                                <a v-if="application.telegram_url" :href="application.telegram_url"
                                    target="_blank">Telegram</a>
                                <a v-if="application.whatsapp_url" :href="application.whatsapp_url"
                                    target="_blank">WhatsApp</a>
                                <a v-if="application.vk_url" :href="application.vk_url" target="_blank">VK</a>
                            </div>
                        </div>

                        <div class="admin-app__wrapper-item-btns">
                            <button class="btn small-btn trans alt"
                                @click="approveApplication(application.id)">Принять</button>
                            <button class="btn small-btn trans alt"
                                @click="rejectApplication(application.id)">Отклонить</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>