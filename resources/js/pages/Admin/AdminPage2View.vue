<script setup>
import AppLayout from '@/layouts/AppLayoutHeaderAdmin.vue'
import { defineProps } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
    users: Array
})

const unblockUser = async (userId) => {
    const result = await Swal.fire({
        title: "Вы уверены?",
        text: "Вы хотите разблокировать этого пользователя?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#4caf50",
        cancelButtonColor: "#aaa",
        confirmButtonText: "Да, разблокировать"
    })

    if (result.isConfirmed) {
        await router.post(route('admin.unblock-user', userId), {}, {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Пользователь разблокирован!",
                    showConfirmButton: false,
                    timer: 1500
                })
            },
            onError: () => {
                Swal.fire({
                    icon: "error",
                    title: "Ошибка при разблокировке пользователя",
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        })
    }
}
</script>

<template>
    <AppLayout>
        <section class="admin-users admin-section admin-body">
            <div class="container wrapper">
                <h1 class="title">Пользователи</h1>
                <div v-if="props.users.length === 0" class="no-users">
                    Нет зарегистрированных пользователей.
                </div>
                <div v-else class="admin-users__wrapper">
                    <div v-for="user in props.users" :key="user.id" class="admin-users__wrapper-item">
                        <div class="admin-users__wrapper-item-block">
                            <div class="admin-users__wrapper-item-block-img">
                                <img :src="user.src" alt="" />
                            </div>
                            <div class="admin-users__wrapper-item-block-content">
                                <h2>{{ user.name }} {{ user.surname }}</h2>
                                <div>
                                    <p>Статус:
                                        <span :class="{ 'blocked': user.status === 'inactive' }">
                                            {{ user.status === 'active' ? 'Активен' : 'Заблокирован' }}
                                        </span>
                                    </p>
                                    <p v-if="user.reason">Причина блокировки: <span>{{ user.reason }}</span></p>
                                    <p>Личный логин: <span>{{ user.login }}</span></p>
                                </div>
                            </div>
                        </div>
                        <button v-if="user.status === 'inactive'" @click="unblockUser(user.id)"
                            class="btn small-btn alt trans unblock-btn">Разблокировать</button>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>