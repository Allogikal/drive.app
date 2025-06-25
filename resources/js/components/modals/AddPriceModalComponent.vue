<script setup>
import Logo from '@/assets/images/logo.png'
import Swal from 'sweetalert2'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    onCancel: Function,
    restaurantId: Number
})
const cocktails = ref([])
const mainMenu = ref([])
const cocktailTitle = ref('')
const cocktailDescription = ref('')
const cocktailPrice = ref(10)
const menuItemTitle = ref('')
const menuItemPrice = ref(10)

const addCocktail = () => {
    if (!cocktailTitle.value || !cocktailDescription.value || !cocktailPrice.value) {
        Swal.fire({
            position: "top-end",
            icon: "info",
            title: "Заполните все поля!",
            showConfirmButton: false,
            timer: 1500
        })
        return
    }

    const data = {
        title: cocktailTitle.value,
        description: cocktailDescription.value,
        price: cocktailPrice.value,
        type: 'cocktail'
    }

    router.post(route('menu.cocktail.store', props.restaurantId), data, {
        preserveScroll: true,
        onSuccess: () => {
            cocktails.value.push(data)
            cocktailTitle.value = ''
            cocktailDescription.value = ''
            cocktailPrice.value = 10
        },
        onError: (errors) => {
            let message = Object.values(errors).flat().join('\n')
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Ошибка при добавлении коктейля:",
                text: message,
                showConfirmButton: false,
                timer: 3000
            })
        }
    })
}

const addMenuItem = () => {
    if (!menuItemTitle.value || !menuItemPrice.value) {
        Swal.fire({
            position: "top-end",
            icon: "info",
            title: "Заполните все поля!",
            showConfirmButton: false,
            timer: 1500
        })
        return
    }

    const data = {
        title: menuItemTitle.value,
        price: menuItemPrice.value,
        type: 'main_course'
    }

    router.post(route('menu.main-course.store', props.restaurantId), data, {
        preserveScroll: true,
        onSuccess: () => {
            mainMenu.value.push(data)
            menuItemTitle.value = ''
            menuItemPrice.value = 10
        },
        onError: (errors) => {
            let message = Object.values(errors).flat().join('\n')
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Ошибка при добавлении блюда:",
                text: message,
                showConfirmButton: false,
                timer: 3000
            })
        }
    })
}
</script>

<template>
    <div class="profile-shop-modal default-modal active">
        <div class="default-modal__wrapper">
            <div class="modal-header">
                <div>
                    <img :src="Logo" alt="Logo">
                </div>
                <p @click.prevent="onCancel()">Отмена</p>
            </div>
            <div class="profile-shop-modal__wrapper">
                <div class="profile-shop-modal__wrapper-list">
                    <h2>Коктейли</h2>
                    <div>
                        <div v-for="(item, index) in cocktails" :key="index"
                            class="profile-shop-modal__wrapper-list-item">
                            <input type="text" :value="item.title" disabled />
                            <textarea disabled :value="item.description"></textarea>
                            <div>
                                <input type="number" disabled :value="item.price" step="10" min="0" required />
                            </div>
                        </div>
                        <div class="profile-shop-modal__wrapper-list-item">
                            <input v-model="cocktailTitle" type="text" placeholder="Название" required />
                            <textarea v-model="cocktailDescription" placeholder="Состав" required></textarea>
                            <div>
                                <input v-model.number="cocktailPrice" type="number" step="10" min="0" value="10"
                                    required />
                                <button @click.prevent="addCocktail">Добавить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile-shop-modal__wrapper-list">
                    <h2>Основное меню</h2>
                    <div>
                        <div v-for="(item, index) in mainMenu" :key="index"
                            class="profile-shop-modal__wrapper-list-item">
                            <input type="text" :value="item.title" />
                            <div>
                                <input type="number" step="10" min="0" :value="item.price" />
                            </div>
                        </div>
                        <div class="profile-shop-modal__wrapper-list-item">
                            <input v-model="menuItemTitle" type="text" placeholder="Название" required />
                            <div>
                                <input v-model.number="menuItemPrice" type="number" step="10" min="0" value="10"
                                    required />
                                <button @click.prevent="addMenuItem">Добавить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>