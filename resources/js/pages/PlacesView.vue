<script setup>
import { ref, computed } from 'vue'
import AppLayout from '@/layouts/AppLayoutHeader.vue'
import { router } from '@inertiajs/vue3'
// IMPORTS IMAGES
import ArrowTR from '@/assets/images/arrow-t-r.svg'
import ShopBack from '@/assets/images/shop-back.jpg'

const props = defineProps({
    places: Array,
    favouriteRestaurantIds: Array,
    searchQuery: String
})

const filters = ref({
    sortByTitle: null,
    worktime: null,
    sortByReviews: null,
    maxRating: null,
    priceFrom: null,
    priceTo: null,
})

const isActive = ref(false)

const toggleActive = () => {
    isActive.value = !isActive.value
}

const isFavourited = (restaurantId) => {
    return props.favouriteRestaurantIds.includes(restaurantId)
}

const filteredPlaces = computed(() => {
    let sortedAndFiltered = [...props.places]

    if (props.searchQuery.trim()) {
        const query = props.searchQuery.toLowerCase()
        sortedAndFiltered = sortedAndFiltered.filter(p =>
            p.title.toLowerCase().includes(query)
        )
    }

    if (filters.value.priceFrom || filters.value.priceTo) {
        sortedAndFiltered = sortedAndFiltered.filter(p => {
            const hasMinPrice = !filters.value.priceFrom || p.average_price >= filters.value.priceFrom
            const hasMaxPrice = !filters.value.priceTo || p.average_price <= filters.value.priceTo
            return hasMinPrice && hasMaxPrice
        })
    }

    if (filters.value.sortByTitle) {
        sortedAndFiltered.sort((a, b) => {
            if (filters.value.sortByTitle === 'asc') {
                return a.title.localeCompare(b.title)
            } else {
                return b.title.localeCompare(a.title)
            }
        })
    }

    if (filters.value.sortByReviews) {
        sortedAndFiltered.sort((a, b) => {
            const countA = a.reviews.length
            const countB = b.reviews.length

            if (filters.value.sortByReviews === 'asc') {
                return countA - countB
            } else {
                return countB - countA
            }
        })
    }

    if (filters.value.maxRating !== null) {
        sortedAndFiltered = sortedAndFiltered.filter(p => p.rating <= filters.value.maxRating)
    }

    return sortedAndFiltered
})

const toggleFavourite = async (restaurantId) => {
    const isCurrentlyFavourited = isFavourited(restaurantId)

    if (isCurrentlyFavourited) {
        await router.delete(route('favourites.remove', restaurantId), {
            preserveScroll: true
        })
    } else {
        await router.post(route('favourites.add', restaurantId), {}, {
            preserveScroll: true
        })
    }
}

const resetFilters = () => {
    filters.value = {
        sortByTitle: null,
        worktime: null,
        sortByReviews: null,
        maxRating: null,
        priceFrom: null,
        priceTo: null,
        searchQuery: null
    }
}

const setMaxRating = (rating) => {
    filters.value.maxRating = filters.value.maxRating === rating ? null : rating
}

const isStarActive = (star) => {
    return filters.value.maxRating >= star
}
</script>

<template>
    <AppLayout>
        <section class="shop-back shop">
            <div class="container-small wrapper">
                <h1 class="title">Куда ты отправишься сегодня?</h1>
                <div :class="{ active: isActive }" class="shop-filter">
                    <h3 @click="toggleActive" :class="{ active: isActive }">Фильтрация</h3>
                    <div class="shop-filter-radios">
                        <div class="shop-filter-radios-item">
                            <h4>По заведению</h4>
                            <div class="shop-filter-radios__wrapper">
                                <div class="shop-filter-radios__wrapper-elem">
                                    <p>Название заведения (А–Я)</p>
                                    <input type="radio" v-model="filters.sortByTitle" value="asc" />
                                </div>
                                <div class="shop-filter-radios__wrapper-elem">
                                    <p>Название заведения (Я–А)</p>
                                    <input type="radio" v-model="filters.sortByTitle" value="desc" />
                                </div>
                                <div class="shop-filter-radios__wrapper-elem">
                                    <p>Работают после 2:00</p>
                                    <input type="radio" v-model="filters.worktime" value="after_2am" />
                                </div>
                            </div>
                        </div>
                        <div class="shop-filter-radios-item">
                            <h4>По количеству отзывов</h4>
                            <div class="shop-filter-radios__wrapper">
                                <div class="shop-filter-radios__wrapper-elem">
                                    <p>Большее количество отзывов</p>
                                    <input type="radio" v-model="filters.sortByReviews" value="desc" />
                                </div>
                                <div class="shop-filter-radios__wrapper-elem">
                                    <p>Меньшее количество отзывов</p>
                                    <input type="radio" v-model="filters.sortByReviews" value="asc" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="shop-filter-rate">
                        <h4>По рейтингу</h4>
                        <div class="shop-filter-stars">
                            <svg @click="setMaxRating(1)" width="28" height="26" viewBox="0 0 28 26"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill="transparent" :class="{ active: isStarActive(1) }"
                                    d="M13.5244 1.08203C13.6648 0.650118 14.246 0.622947 14.4414 1.00098L14.4756 1.08203L16.8926 8.51953C17.0935 9.13724 17.6688 9.55549 18.3184 9.55566H26.1396C26.5934 9.556 26.7988 10.1007 26.5 10.4033L26.4336 10.46L20.1064 15.0566C19.5807 15.4386 19.3607 16.1163 19.5615 16.7344L21.9775 24.1719C22.1272 24.6325 21.6008 25.015 21.209 24.7305L14.8818 20.1338C14.389 19.7757 13.7321 19.7531 13.2188 20.0664L13.1182 20.1338L6.79102 24.7305C6.39918 25.015 5.87281 24.6325 6.02246 24.1719L8.43848 16.7344C8.63929 16.1163 8.41929 15.4386 7.89355 15.0566L1.56641 10.46C1.17526 10.1752 1.37648 9.55603 1.86035 9.55566H9.68164C10.3312 9.55549 10.9065 9.13723 11.1074 8.51953L13.5244 1.08203Z"
                                    stroke="#5771FC" class="trans" />
                            </svg>
                            <svg @click="setMaxRating(2)" width="28" height="26" viewBox="0 0 28 26"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill="transparent" :class="{ active: isStarActive(2) }"
                                    d="M13.5244 1.08203C13.6648 0.650118 14.246 0.622947 14.4414 1.00098L14.4756 1.08203L16.8926 8.51953C17.0935 9.13724 17.6688 9.55549 18.3184 9.55566H26.1396C26.5934 9.556 26.7988 10.1007 26.5 10.4033L26.4336 10.46L20.1064 15.0566C19.5807 15.4386 19.3607 16.1163 19.5615 16.7344L21.9775 24.1719C22.1272 24.6325 21.6008 25.015 21.209 24.7305L14.8818 20.1338C14.389 19.7757 13.7321 19.7531 13.2188 20.0664L13.1182 20.1338L6.79102 24.7305C6.39918 25.015 5.87281 24.6325 6.02246 24.1719L8.43848 16.7344C8.63929 16.1163 8.41929 15.4386 7.89355 15.0566L1.56641 10.46C1.17526 10.1752 1.37648 9.55603 1.86035 9.55566H9.68164C10.3312 9.55549 10.9065 9.13723 11.1074 8.51953L13.5244 1.08203Z"
                                    stroke="#5771FC" class="trans" />
                            </svg>
                            <svg @click="setMaxRating(3)" width="28" height="26" viewBox="0 0 28 26"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill="transparent" :class="{ active: isStarActive(3) }"
                                    d="M13.5244 1.08203C13.6648 0.650118 14.246 0.622947 14.4414 1.00098L14.4756 1.08203L16.8926 8.51953C17.0935 9.13724 17.6688 9.55549 18.3184 9.55566H26.1396C26.5934 9.556 26.7988 10.1007 26.5 10.4033L26.4336 10.46L20.1064 15.0566C19.5807 15.4386 19.3607 16.1163 19.5615 16.7344L21.9775 24.1719C22.1272 24.6325 21.6008 25.015 21.209 24.7305L14.8818 20.1338C14.389 19.7757 13.7321 19.7531 13.2188 20.0664L13.1182 20.1338L6.79102 24.7305C6.39918 25.015 5.87281 24.6325 6.02246 24.1719L8.43848 16.7344C8.63929 16.1163 8.41929 15.4386 7.89355 15.0566L1.56641 10.46C1.17526 10.1752 1.37648 9.55603 1.86035 9.55566H9.68164C10.3312 9.55549 10.9065 9.13723 11.1074 8.51953L13.5244 1.08203Z"
                                    stroke="#5771FC" class="trans" />
                            </svg>
                            <svg @click="setMaxRating(4)" width="28" height="26" viewBox="0 0 28 26"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill="transparent" :class="{ active: isStarActive(4) }"
                                    d="M13.5244 1.08203C13.6648 0.650118 14.246 0.622947 14.4414 1.00098L14.4756 1.08203L16.8926 8.51953C17.0935 9.13724 17.6688 9.55549 18.3184 9.55566H26.1396C26.5934 9.556 26.7988 10.1007 26.5 10.4033L26.4336 10.46L20.1064 15.0566C19.5807 15.4386 19.3607 16.1163 19.5615 16.7344L21.9775 24.1719C22.1272 24.6325 21.6008 25.015 21.209 24.7305L14.8818 20.1338C14.389 19.7757 13.7321 19.7531 13.2188 20.0664L13.1182 20.1338L6.79102 24.7305C6.39918 25.015 5.87281 24.6325 6.02246 24.1719L8.43848 16.7344C8.63929 16.1163 8.41929 15.4386 7.89355 15.0566L1.56641 10.46C1.17526 10.1752 1.37648 9.55603 1.86035 9.55566H9.68164C10.3312 9.55549 10.9065 9.13723 11.1074 8.51953L13.5244 1.08203Z"
                                    stroke="#5771FC" class="trans" />
                            </svg>
                            <svg @click="setMaxRating(5)" width="28" height="26" viewBox="0 0 28 26"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill="transparent" :class="{ active: isStarActive(5) }"
                                    d="M13.5244 1.08203C13.6648 0.650118 14.246 0.622947 14.4414 1.00098L14.4756 1.08203L16.8926 8.51953C17.0935 9.13724 17.6688 9.55549 18.3184 9.55566H26.1396C26.5934 9.556 26.7988 10.1007 26.5 10.4033L26.4336 10.46L20.1064 15.0566C19.5807 15.4386 19.3607 16.1163 19.5615 16.7344L21.9775 24.1719C22.1272 24.6325 21.6008 25.015 21.209 24.7305L14.8818 20.1338C14.389 19.7757 13.7321 19.7531 13.2188 20.0664L13.1182 20.1338L6.79102 24.7305C6.39918 25.015 5.87281 24.6325 6.02246 24.1719L8.43848 16.7344C8.63929 16.1163 8.41929 15.4386 7.89355 15.0566L1.56641 10.46C1.17526 10.1752 1.37648 9.55603 1.86035 9.55566H9.68164C10.3312 9.55549 10.9065 9.13723 11.1074 8.51953L13.5244 1.08203Z"
                                    stroke="#5771FC" class="trans" />
                            </svg>
                        </div>
                    </div>
                    <div class="shop-filter-rate-price">
                        <h4>По цене</h4>
                        <div>
                            <input type="number" v-model.number="filters.priceFrom" placeholder="От" min="0" />
                            <span>—</span>
                            <input type="number" v-model.number="filters.priceTo" placeholder="До" min="0" />
                        </div>
                    </div>
                    <button @click="resetFilters" class="shop-filter-btn trans">Сбросить</button>
                </div>
                <div class="shop__wrapper">
                    <div v-for="place in filteredPlaces" :key="place.id">
                        <div class="shop__wrapper-item">
                            <div class="shop__wrapper-item-img">
                                <img :src="place?.images[0]?.src ? place?.images[0]?.src : ShopBack" alt="ShopBack" />
                                <div class="shop__wrapper-item-img-like">
                                    <button @click.prevent="toggleFavourite(place.id)">
                                        <svg width="34" height="31" viewBox="0 0 34 31" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.3919 4.76409C11.6405 -4.28878 0 0.642929 0 10.5045C0 17.9099 15.9585 29.4938 17.3919 31C18.8351 29.4938 34 17.9099 34 10.5045C34 0.717694 23.154 -4.28878 17.3919 4.76409Z"
                                                :fill="isFavourited(place.id) ? '#5871FB' : 'transparent'"
                                                stroke="#5871FB" class="trans" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="shop__wrapper-item-content">
                                <h2>{{ place.title }}</h2>
                                <p>{{ place.description }}</p>
                                <ul>
                                    <li>{{ place.address }}</li>
                                    <li>Средний чек: {{ place.average_price }}</li>
                                </ul>
                            </div>
                            <div class="shop__wrapper-item-rate">
                                <p>{{ place.rating }}</p>
                            </div>
                            <a :href="route('place-profile', place.id)" class="shop__wrapper-item-href">
                                <img :src="ArrowTR" alt="ArrowTR" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
