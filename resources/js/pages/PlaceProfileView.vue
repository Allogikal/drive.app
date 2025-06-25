<script setup>
import AppLayout from '@/layouts/AppLayoutHeader.vue'
import AddPriceModalComponent from '@/components/modals/AddPriceModalComponent.vue'
// IMAGES IMPORTS
import ShopListImage from '@/assets/images/shop-list-img.png';
import TgIcon from '@/assets/images/tg-icon.svg';
import WhatsappIcon from '@/assets/images//whatsapp-icon.svg';
import VkIcon from '@/assets/images/vk-icon.svg';
import { onMounted, ref, computed } from 'vue';
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const isAddPriceModal = ref(false)
const reviewText = ref('')
const selectedRating = ref(null)

const props = defineProps({
    place: Object,
    isFavourited: Boolean,
    user: Object,
    worktime_start: String,
    worktime_end: String
})

const isVisited = ref(props.isVisited ?? false)

const toggleVisited = async () => {
    if (isVisited.value) {
        await router.delete(route('restaurants.unvisit', props.place.id), {
            preserveScroll: true
        })
    } else {
        await router.post(route('restaurants.visit', props.place.id), {}, {
            preserveScroll: true
        })
    }

    isVisited.value = !isVisited.value
}

const toggleAddPriceModal = () => {
    isAddPriceModal.value = !isAddPriceModal.value
}

const toggleFavourite = async (restaurantId) => {
    const isCurrentlyFavourited = props.isFavourited

    if (isCurrentlyFavourited) {
        await router.delete(route('favourites.remove', restaurantId), {
            preserveScroll: true,
            onSuccess: () => {
                if (!window.location.href.includes('login')) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Удалено из избранного!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            onError: (errors) => {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Произошла ошибка.",
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        })
    } else {
        await router.post(route('favourites.add', restaurantId), {}, {
            preserveScroll: true,
            onSuccess: () => {
                if (!window.location.href.includes('login')) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Добавлено в избранное!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            onError: (errors) => {
                if (errors.response?.status === 422) {
                    let errors = Object.values(error.response.data.errors).flat().join('\n')
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: error.response.data.message || 'Ошибка',
                        text: errors,
                        showConfirmButton: false,
                        timer: 3000
                    })
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Произошла ошибка.",
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            }
        })
    }
}

const isOwner = computed(() => {
    return props.user && (
        props.user.id === props.place.user_id
    )
})

const deleteRestaurant = async () => {
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
        await router.delete(route('restaurants.destroy', props.place.id), {
            onSuccess: () => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Заведение успешно удалено!",
                    showConfirmButton: false,
                    timer: 1500
                })
            },
            onError: (errors) => {
                if (errors.response?.status === 422) {
                    let errors = Object.values(error.response.data.errors).flat().join('\n')
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: error.response.data.message || 'Ошибка',
                        text: errors,
                        showConfirmButton: false,
                        timer: 3000
                    })
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Произошла ошибка.",
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            }
        })
    }
}

const submitReview = async () => {
    if (!reviewText.value.trim()) {
        Swal.fire({
            icon: "info",
            title: "Пожалуйста, напишите отзыв",
            showConfirmButton: false,
            timer: 1500
        });
        return
    }
    if (selectedRating.value < 1 || selectedRating.value > 5) {
        Swal.fire({
            icon: "info",
            title: "Пожалуйста, выберите оценку от 1 до 5",
            showConfirmButton: false,
            timer: 1500
        });
        return
    }
    try {
        await router.post(route('review.store', props.place.id), {
            content: reviewText.value,
            rate: selectedRating.value
        }, {
            preserveScroll: true,
            onSuccess: () => {
                if (!props.user) {
                    Swal.fire({
                        icon: "info",
                        title: "Необходимо авторизоваться!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                reviewText.value = ''
                selectedRating.value = null
                if (!window.location.href.includes('login')) {
                    Swal.fire({
                        icon: "success",
                        title: "Отзыв успешно добавлен!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            onError: (errors) => {
                let message = Object.values(errors).flat().join('\n');
                Swal.fire({
                    icon: "error",
                    title: "Ошибка",
                    text: message,
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        })
    } catch (error) {
        console.error(error)
        Swal.fire({
            icon: "error",
            title: "Произошла ошибка.",
            showConfirmButton: false,
            timer: 1500
        });
    }
}

const setRating = (rating) => {
    selectedRating.value = rating
}

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return `${(date.getDate()).toString().padStart(2, '0')}.${(date.getMonth() + 1).toString().padStart(2, '0')}.${date.getFullYear()}`
}

onMounted(() => {
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 10,
        freeMode: true,
        watchSlidesProgress: true,
        breakpoints: {
            300: {
                slidesPerView: 2,
            },
            800: {
                slidesPerView: 3,
            },
            1200: {
                slidesPerView: 4,
            }
        }
    });
    var swiper2 = new Swiper(".mySwiper2", {
        spaceBetween: 10,
        thumbs: {
            swiper: swiper,
        },
    });
})
</script>

<template>
    <AppLayout>
        <main>
            <section class="profile-shop-main section">
                <div class="container-small profile-shop-main__wrapper">
                    <div class="profile-shop-main__wrapper--content">
                        <div class="profile-shop-main__wrapper--content-img">
                            <div class="swiper mySwiper2">
                                <div class="swiper-wrapper">
                                    <div v-for="image in props.place.images" :key="place.images.id"
                                        class="swiper-slide">
                                        <img :src="image.src" alt="image" />
                                    </div>
                                </div>
                            </div>
                            <div class="profile-shop-main__wrapper--content-img-icon">
                                <svg width="51" height="47" viewBox="0 0 51 47" fill="none"
                                    @click.prevent="toggleFavourite(place.id)" xmlns="http://www.w3.org/2000/svg">
                                    <path class="trans"
                                        d="M26.0878 7.22297C17.4608 -6.50235 0 0.974764 0 15.9262C0 27.1536 23.9378 44.7164 26.0878 47C28.2527 44.7164 51 27.1536 51 15.9262C51 1.08812 34.731 -6.50235 26.0878 7.22297Z"
                                        :fill="isFavourited ? '#5871FB' : 'transparent'" stroke="#5871FC" />
                                </svg>
                            </div>
                        </div>
                        <div class="profile-shop-main__wrapper--content-block">
                            <div class="profile-shop-main__wrapper--content-block-info">
                                <div>
                                    <h1>{{ place?.title }}</h1>
                                    <span>{{ place?.rating }}</span>
                                </div>
                                <p>{{ place?.description }}</p>
                            </div>
                            <div class="profile-shop-main__wrapper--content-block-other">
                                <button @click.prevent="toggleVisited">
                                    {{ isVisited ? 'Посетил' : 'Посетить' }}
                                </button>
                                <div thumbsSlider=""
                                    class="swiper mySwiper profile-shop-main__wrapper--content-block-other-gallery">
                                    <div class="swiper-wrapper">
                                        <div v-for="image in props.place.images" :key="place.images.id"
                                            class="swiper-slide">
                                            <img :src="image.src" alt="image" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-shop__wrapper-contacts">
                        <div class="profile-shop__wrapper-contacts-info">
                            <div class="profile-shop__wrapper-contacts-info-block">
                                <div>
                                    <h2>Время работы</h2>
                                    <p>{{ worktime_start }} – {{ worktime_end }}</p>
                                </div>
                                <div>
                                    <h2>Особенности заведения</h2>
                                    <p>{{ place?.description }}</p>
                                </div>
                                <button v-if="isOwner" @click.prevent="toggleAddPriceModal">{{
                                    place?.menu_positions.length === 0 ? 'Добавить' : 'Редактировать' }}
                                    прайс</button>
                            </div>
                            <div class="profile-shop__wrapper-contacts-info-block">
                                <div>
                                    <h2>Контакты</h2>
                                    <a :href="'tel:' + place?.phone">{{ place?.phone }}</a>
                                    <a :href="'mailto:' + place?.restaurant_mail">{{ place?.restaurant_mail }}</a>
                                    <a :href="place?.restaurant_site_url">{{ place?.restaurant_site_url }}</a>
                                </div>
                                <div>
                                    <h2>Адрес</h2>
                                    <p>{{ place?.address }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="profile-shop__wrapper-contacts-social">
                            <h2 v-if="place?.telegram_url && place?.whatsapp_url && place?.vk_url">Социальные сети</h2>
                            <div class="profile-shop__wrapper-contacts-social-block">
                                <div>
                                    <a v-if="place?.telegram_url" :href="place?.telegram_url" target="_blank">
                                        <img :src="TgIcon" alt="TgIcon">
                                    </a>
                                    <a v-if="place?.whatsapp_url" :href="place?.whatsapp_url" target="_blank">
                                        <img :src="WhatsappIcon" alt="WhatsappIcon">
                                    </a>
                                    <a v-if="place?.vk_url" :href="place?.vk_url" target="_blank">
                                        <img :src="VkIcon" alt="VkIcon">
                                    </a>
                                </div>
                                <a :href="route('map')">Показать на карте</a>
                                <button v-if="props.user?.id === place.user_id || props.user?.login === 'admin'"
                                    @click.prevent="deleteRestaurant()">Удалить
                                    заведение</button>
                                <button v-if="isOwner">
                                    <a :href="route('edit-place', place.id)">
                                        <button>Редактировать заведение</button>
                                    </a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <AddPriceModalComponent :restaurantId="place.id" :on-cancel="toggleAddPriceModal" v-show="isAddPriceModal">
            </AddPriceModalComponent>

            <section class="shop-list section">
                <div class="wrapper">
                    <h2 class="container-small">Прайс лист</h2>
                    <div class="shop-list__wrapper">
                        <div class="shop-list__wrapper-img">
                            <img :src="ShopListImage" alt="ShopListImage">
                        </div>
                        <div class="container-small">
                            <div class="shop-list__wrapper-content">
                                <div class="shop-list__wrapper-content-block">
                                    <h3>Коктейли</h3>
                                    <ul>
                                        <li v-for="position in place.menu_positions" :key="position.id">
                                            <div v-if="position?.type === 'cocktail'">
                                                <h4>{{ position?.title }}</h4>
                                                <p>{{ position?.description }}</p>
                                                <span>{{ position?.price }} ₽</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="shop-list__wrapper-content-block">
                                    <h3>Основное меню</h3>
                                    <ul>
                                        <li v-for="position in place.menu_positions" :key="position.id">
                                            <div v-if="position?.type === 'main_course'">
                                                <h4>{{ position?.title }}</h4>
                                                <span>{{ position?.price }} ₽</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="shop-review section">
                <div class="wrapper container-small">
                    <div class="shop-review__title">
                        <h2>Отзывы</h2>
                        <div>
                            <svg width="28" height="26" viewBox="0 0 28 26" fill="none" @click="setRating(1)"
                                xmlns="http://www.w3.org/2000/svg">
                                <path class="trans" fill="transparent" :class="{ active: selectedRating >= 1 }"
                                    d="M13.5244 1.08203C13.6648 0.650118 14.246 0.622947 14.4414 1.00098L14.4756 1.08203L16.8926 8.51953C17.0935 9.13724 17.6688 9.55549 18.3184 9.55566H26.1396C26.5934 9.556 26.7988 10.1007 26.5 10.4033L26.4336 10.46L20.1064 15.0566C19.5807 15.4386 19.3607 16.1163 19.5615 16.7344L21.9775 24.1719C22.1272 24.6325 21.6008 25.015 21.209 24.7305L14.8818 20.1338C14.389 19.7757 13.7321 19.7531 13.2188 20.0664L13.1182 20.1338L6.79102 24.7305C6.39918 25.015 5.87281 24.6325 6.02246 24.1719L8.43848 16.7344C8.63929 16.1163 8.41929 15.4386 7.89355 15.0566L1.56641 10.46C1.17526 10.1752 1.37648 9.55603 1.86035 9.55566H9.68164C10.3312 9.55549 10.9065 9.13723 11.1074 8.51953L13.5244 1.08203Z"
                                    stroke="#5871FC" />
                            </svg>
                            <svg width="28" height="26" viewBox="0 0 28 26" fill="none" @click="setRating(2)"
                                xmlns="http://www.w3.org/2000/svg">
                                <path class="trans" fill="transparent" :class="{ active: selectedRating >= 2 }"
                                    d="M13.5244 1.08203C13.6648 0.650118 14.246 0.622947 14.4414 1.00098L14.4756 1.08203L16.8926 8.51953C17.0935 9.13724 17.6688 9.55549 18.3184 9.55566H26.1396C26.5934 9.556 26.7988 10.1007 26.5 10.4033L26.4336 10.46L20.1064 15.0566C19.5807 15.4386 19.3607 16.1163 19.5615 16.7344L21.9775 24.1719C22.1272 24.6325 21.6008 25.015 21.209 24.7305L14.8818 20.1338C14.389 19.7757 13.7321 19.7531 13.2188 20.0664L13.1182 20.1338L6.79102 24.7305C6.39918 25.015 5.87281 24.6325 6.02246 24.1719L8.43848 16.7344C8.63929 16.1163 8.41929 15.4386 7.89355 15.0566L1.56641 10.46C1.17526 10.1752 1.37648 9.55603 1.86035 9.55566H9.68164C10.3312 9.55549 10.9065 9.13723 11.1074 8.51953L13.5244 1.08203Z"
                                    stroke="#5871FC" />
                            </svg>
                            <svg width="28" height="26" viewBox="0 0 28 26" fill="none" @click="setRating(3)"
                                xmlns="http://www.w3.org/2000/svg">
                                <path class="trans" fill="transparent" :class="{ active: selectedRating >= 3 }"
                                    d="M13.5244 1.08203C13.6648 0.650118 14.246 0.622947 14.4414 1.00098L14.4756 1.08203L16.8926 8.51953C17.0935 9.13724 17.6688 9.55549 18.3184 9.55566H26.1396C26.5934 9.556 26.7988 10.1007 26.5 10.4033L26.4336 10.46L20.1064 15.0566C19.5807 15.4386 19.3607 16.1163 19.5615 16.7344L21.9775 24.1719C22.1272 24.6325 21.6008 25.015 21.209 24.7305L14.8818 20.1338C14.389 19.7757 13.7321 19.7531 13.2188 20.0664L13.1182 20.1338L6.79102 24.7305C6.39918 25.015 5.87281 24.6325 6.02246 24.1719L8.43848 16.7344C8.63929 16.1163 8.41929 15.4386 7.89355 15.0566L1.56641 10.46C1.17526 10.1752 1.37648 9.55603 1.86035 9.55566H9.68164C10.3312 9.55549 10.9065 9.13723 11.1074 8.51953L13.5244 1.08203Z"
                                    stroke="#5871FC" />
                            </svg>
                            <svg width="28" height="26" viewBox="0 0 28 26" fill="none" @click="setRating(4)"
                                xmlns="http://www.w3.org/2000/svg">
                                <path class="trans" fill="transparent" :class="{ active: selectedRating >= 4 }"
                                    d="M13.5244 1.08203C13.6648 0.650118 14.246 0.622947 14.4414 1.00098L14.4756 1.08203L16.8926 8.51953C17.0935 9.13724 17.6688 9.55549 18.3184 9.55566H26.1396C26.5934 9.556 26.7988 10.1007 26.5 10.4033L26.4336 10.46L20.1064 15.0566C19.5807 15.4386 19.3607 16.1163 19.5615 16.7344L21.9775 24.1719C22.1272 24.6325 21.6008 25.015 21.209 24.7305L14.8818 20.1338C14.389 19.7757 13.7321 19.7531 13.2188 20.0664L13.1182 20.1338L6.79102 24.7305C6.39918 25.015 5.87281 24.6325 6.02246 24.1719L8.43848 16.7344C8.63929 16.1163 8.41929 15.4386 7.89355 15.0566L1.56641 10.46C1.17526 10.1752 1.37648 9.55603 1.86035 9.55566H9.68164C10.3312 9.55549 10.9065 9.13723 11.1074 8.51953L13.5244 1.08203Z"
                                    stroke="#5871FC" />
                            </svg>
                            <svg width="28" height="26" viewBox="0 0 28 26" fill="none" @click="setRating(5)"
                                xmlns="http://www.w3.org/2000/svg">
                                <path class="trans" fill="transparent" :class="{ active: selectedRating >= 5 }"
                                    d="M13.5244 1.08203C13.6648 0.650118 14.246 0.622947 14.4414 1.00098L14.4756 1.08203L16.8926 8.51953C17.0935 9.13724 17.6688 9.55549 18.3184 9.55566H26.1396C26.5934 9.556 26.7988 10.1007 26.5 10.4033L26.4336 10.46L20.1064 15.0566C19.5807 15.4386 19.3607 16.1163 19.5615 16.7344L21.9775 24.1719C22.1272 24.6325 21.6008 25.015 21.209 24.7305L14.8818 20.1338C14.389 19.7757 13.7321 19.7531 13.2188 20.0664L13.1182 20.1338L6.79102 24.7305C6.39918 25.015 5.87281 24.6325 6.02246 24.1719L8.43848 16.7344C8.63929 16.1163 8.41929 15.4386 7.89355 15.0566L1.56641 10.46C1.17526 10.1752 1.37648 9.55603 1.86035 9.55566H9.68164C10.3312 9.55549 10.9065 9.13723 11.1074 8.51953L13.5244 1.08203Z"
                                    stroke="#5871FC" />
                            </svg>
                        </div>
                    </div>
                    <div class="shop-review__wrapper">
                        <div class="shop-review__wrapper--form">
                            <textarea v-model="reviewText" id="review" placeholder="Оставьте отзыв"></textarea>
                            <button @click.prevent="submitReview">Отправить</button>
                        </div>
                        <div v-for="review in place.reviews" :key="review.id" class="shop-review__wrapper-item">
                            <div class="shop-review__wrapper-item-img">
                                <div>
                                    <img :src="review.user.src" alt="ProfileShopTest">
                                </div>
                                <span>{{ formatDate(review.created_at) }}</span>
                            </div>
                            <div class="shop-review__wrapper-item-content">
                                <div class="shop-review__wrapper-item-content-main">
                                    <h3>{{ review.user.name }} {{ review.user.surname }}</h3>
                                    <p>{{ review.content }}</p>
                                </div>
                                <div class="shop-review__wrapper-item-content-likes">
                                    <div>
                                        <div>
                                            <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path class="trans"
                                                    d="M8.54894 0.927051C8.8483 0.00574017 10.1517 0.00573993 10.4511 0.927051L11.8574 5.25532C11.9913 5.66734 12.3752 5.9463 12.8085 5.9463H17.3595C18.3282 5.9463 18.731 7.18592 17.9473 7.75532L14.2654 10.4303C13.9149 10.685 13.7683 11.1364 13.9021 11.5484L15.3085 15.8766C15.6078 16.798 14.5533 17.5641 13.7696 16.9947L10.0878 14.3197C9.7373 14.065 9.2627 14.065 8.91221 14.3197L5.23037 16.9947C4.44665 17.5641 3.39217 16.798 3.69153 15.8766L5.09787 11.5484C5.23174 11.1364 5.08508 10.685 4.7346 10.4303L1.05275 7.75532C0.269035 7.18592 0.67181 5.9463 1.64053 5.9463H6.19155C6.62477 5.9463 7.00873 5.66734 7.1426 5.25532L8.54894 0.927051Z"
                                                    stroke="#F986A1" fill="#F986A1" />
                                            </svg>
                                        </div>
                                        <p>{{ review.rate }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </AppLayout>
</template>