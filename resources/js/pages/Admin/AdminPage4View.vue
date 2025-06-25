<script setup>
import AppLayout from '@/layouts/AppLayoutHeaderAdmin.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import Star from '@/assets/images/Star.svg'
import Close from '@/assets/images/+.svg'

const props = defineProps({
    reviews: Array
})

const isBanModalOpen = ref(false)
const currentUserId = ref(null)
const reason = ref('')

const openBanModal = (userId) => {
    currentUserId.value = userId
    isBanModalOpen.value = true
}

const closeBanModal = () => {
    isBanModalOpen.value = false
    currentReviewId.value = null
    reason.value = ''
}

const blockUser = async () => {
    if (!reason.value.trim()) {
        Swal.fire({
            icon: "error",
            title: "Причина не указана!",
            showConfirmButton: false,
            timer: 1500
        })
        return
    }

    await router.post(route('admin.block-user', currentUserId.value), {
        reason: reason.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            closeBanModal()
            Swal.fire({
                icon: "success",
                title: "Пользователь заблокирован!",
                showConfirmButton: false,
                timer: 1500
            })
        },
        onError: () => {
            closeBlockModal()
            Swal.fire({
                icon: "error",
                title: "Ошибка при блокировке пользователя",
                showConfirmButton: false,
                timer: 1500
            })
        }
    })
}

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

const deleteReview = (reviewId) => {
    Swal.fire({
        title: 'Вы уверены?',
        text: 'Этот отзыв будет удален без возможности восстановления.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Отменить'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.review.delete', reviewId), {
                preserveScroll: true
            })
        }
    })
}
</script>

<template>
    <AppLayout>
        <section class="admin-reviews admin-section admin-body">
            <div class="container wrapper">
                <h1 class="title">Отзывы</h1>
                <div v-if="props.reviews.length === 0" class="no-reviews">
                    Нет активных отзывов.
                </div>
                <div v-else class="admin-reviews__wrapper">
                    <div v-for="review in props.reviews" :key="review.id" class="admin-reviews__wrapper-item">
                        <div class="admin-reviews__wrapper-item-img">
                            <div>
                                <img :src="review.user.src" alt="User" />
                            </div>
                            <span>{{ new Date(review.created_at).toLocaleDateString() }}</span>
                        </div>
                        <div class="admin-reviews__wrapper-item-content">
                            <div class="admin-reviews__wrapper-item-content-headers">
                                <h2>{{ review.restaurant.title }} — {{ review.user.name }} {{ review.user.surname }}
                                </h2>
                                <button @click.prevent="deleteReview(review.id)">
                                    <img :src="Close" alt="Close">
                                </button>
                                <button v-if="review.user.status === 'active'"
                                    @click.prevent="openBanModal(review.user.id)">БАН</button>
                                <button v-else @click.prevent="unblockUser(review.user.id)">РАЗБАН</button>
                            </div>
                            <p>{{ review.content }}</p>
                            <div class="admin-reviews__wrapper-item-content-footer">
                                <div>
                                    <div>
                                        <img :src="Star" alt="Star" />
                                    </div>
                                    <p>Оценка: {{ review.rate }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="isBanModalOpen" class="admin-reviews-modal">
                <div class="admin-reviews-modal__wrapper">
                    <h3>Причина блокировки</h3>
                    <textarea v-model="reason" placeholder="Напишите причину..."></textarea>
                    <div>
                        <button @click="blockUser">Подтвердить</button>
                        <button @click="closeBanModal">Отменить</button>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>