<script setup>
import AppLayout from '@/layouts/AppLayoutHeader.vue'
import { usePage, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import Swal from 'sweetalert2'
import axios from 'axios'

// IMAGES IMPORTS
import ProfileImg1 from '@/assets/images/profile-img-1.png'
import ProfileImg2 from '@/assets/images/profile-img-2.png'
import ProfileShopTest from '@/assets/images/profile-shop-test.jpg'
import Upload from '@/assets/images/upload.svg'
import Logo from '@/assets/images/logo.png'

const user = computed(() => usePage().props.auth.user)
const favourites = computed(() => usePage().props.favourites)
const reviews = computed(() => usePage().props.reviews)
const visited = computed(() => usePage().props.visited)
const userImages = computed(() => usePage().props.userImages)

const showModal = ref(false)
const showModalAddImage = ref(false)
const name = ref(user.value.name)
const surname = ref(user.value.surname)
const date = ref(user.value.date)
const sex = ref(user.value.sex)
const file = ref(null)
const imageDescription = ref('')

console.log(userImages.value)

const onFileChangeImagesUser = (event) => {
    return file.value = event.target.files[0]
}

const uploadUserImage = async () => {
    if (!file.value) {
        Swal.fire({
            icon: "info",
            title: "Выберите файл!",
            showConfirmButton: false,
            timer: 1500
        })
        return
    }
    if (!imageDescription.value.trim()) {
        Swal.fire({
            icon: "error",
            title: "Описание не может быть пустым!",
            showConfirmButton: false,
            timer: 1500
        })
        return
    }
    try {
        const formData = new FormData()
        formData.append('image', file.value)
        formData.append('description', imageDescription.value)

        await router.post(route('user.upload-image'), formData, {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Фотография добавлена!",
                    showConfirmButton: false,
                    timer: 1500
                })
                closeModalAddImage()
            },
            onError: (errors) => {
                let message = Object.values(errors).flat().join('\n')
                Swal.fire({
                    icon: "error",
                    title: "Ошибка при загрузке:",
                    text: message,
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        })
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Произошла ошибка.",
            showConfirmButton: false,
            timer: 1500
        })
    }
}

// TABS
const tabs = [
    { id: 'favorite', title: 'Избранное' },
    { id: 'reviews', title: 'Отзывы' },
    { id: 'images', title: 'Фотографии' },
    { id: 'places', title: 'Мои места' }
]

const activeTab = ref('favorite')

const setActiveTab = (id) => {
    activeTab.value = id
}

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

const deleteAccount = async () => {
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
        await router.delete(route('profile.delete'), {
            onSuccess: () => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Аккаунт успешно удален!",
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

const calculateAge = (dateString) => {
    if (!dateString) return ''
    const today = new Date()
    const birthDate = new Date(dateString)
    let age = today.getFullYear() - birthDate.getFullYear()
    const monthDiff = today.getMonth() - birthDate.getMonth()

    if (
        monthDiff < 0 ||
        (monthDiff === 0 && today.getDate() < birthDate.getDate())
    ) {
        age--
    }

    return age
}

const formatDate = (dateString) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    const day = String(date.getDate()).padStart(2, '0')
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const year = date.getFullYear()
    return `${day}.${month}.${year}`
}

const openModal = () => {
    showModal.value = true
}

const openModalAddImage = () => {
    showModalAddImage.value = true
}

const closeModal = () => {
    showModal.value = false
}

const closeModalAddImage = () => {
    showModalAddImage.value = false
}

const onFileChange = (event) => {
    const selectedFile = event.target.files[0];
    if (selectedFile) {
        file.value = selectedFile;
        previewImage.value = URL.createObjectURL(selectedFile);
    }
};

const updateProfile = async () => {
    let data = new FormData();
    data.append('name', name.value);
    data.append('surname', surname.value);
    data.append('date', date.value);
    data.append('sex', sex.value);
    if (file.value) {
        data.append('src', file.value);
    }
    try {
        await router.post(route('profile.update'), data, {
            preserveScroll: true,
            onSuccess: (page) => {
                if (page.props.flash?.success) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: page.props.flash.success,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    Object.assign(user.value, data);
                }
                closeModal();
            },
            onError: (errors) => {
                let errorMessages = Object.values(errors).flat().join('\n');
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Ошибка ввода данных",
                    text: errorMessages,
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    } catch (error) {
        console.error(error);
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Произошла ошибка.",
            showConfirmButton: false,
            timer: 1500
        });
    }
}

const deleteOfFavourite = async (restaurantId) => {
    await router.delete(route('favourites.remove', restaurantId), {
        preserveScroll: true
    })
}
</script>

<template>
    <AppLayout>
        <main>
            <section class="profile section">
                <div class="profile-img">
                    <img :src="ProfileImg1" alt="ProfileImg1">
                </div>
                <div class="profile__wrapper container-small">
                    <div class="profile__wrapper-info">
                        <div class="profile__wrapper-info-img">
                            <img :src="user.src" alt="ProfileShopTest">
                        </div>
                        <div class="profile__wrapper-info-content">
                            <div class="profile__wrapper-info-content-info">
                                <h1>{{ user?.name }} {{ user?.surname }}</h1>
                                <p><span>Был(-а) в сети: {{ formatDate(user?.updated_at) }}</span></p>
                                <p>Логин: <span>{{ user?.login }}</span></p>
                                <p>Возраст: <span>{{ calculateAge(user?.date) }}</span></p>
                                <p>Пол: <span>{{ user?.sex === 'male' ? 'Муж.' : 'Жен.' }}</span></p>
                            </div>
                            <div class="profile__wrapper-info-content-btns">
                                <button @click.prevent="openModal" class="alt">Редактировать профиль</button>
                                <button @click="deleteAccount">Удалить аккаунт</button>
                                <button>
                                    <a :href="route('create-place')">Создать заведение</a>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button @click.prevent="logout" class="profile__wrapper-btn">Выход</button>
                    <div class="profile__wrapper-content">
                        <div class="profile__wrapper-content-btns">
                            <div v-for="(tab, index) in tabs" :key="index"
                                :class="['trans', { active: activeTab === tab.id }]" @click="setActiveTab(tab.id)">
                                {{ tab.title }}
                            </div>
                        </div>
                        <div class="profile__wrapper-content-main">
                            <div v-show="activeTab === 'favorite'"
                                class="profile__wrapper-content-main-favorite profile__wrapper-content-main-scroll active">
                                <div v-for="place in favourites" :key="place.id"
                                    class="profile__wrapper-content-main-favorite-item">
                                    <div class="profile__wrapper-content-main-favorite-item-img">
                                        <img :src="place.restaurant.images[0].src ? place.restaurant.images[0].src : ProfileImg1"
                                            alt="ProfileImg1">
                                        <div>
                                            <svg width="23" height="21" viewBox="0 0 23 21" fill="none"
                                                @click.prevent="deleteOfFavourite(place?.restaurant?.id)"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.7637 3.28277C7.89259 -2.81051 0.0576172 0.508913 0.0576172 7.14653C0.0576172 12.1309 10.7989 19.9277 11.7637 20.9416C12.7351 19.9277 22.9422 12.1309 22.9422 7.14653C22.9422 0.559235 15.642 -2.81051 11.7637 3.28277Z"
                                                    fill="#5871FC" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="profile__wrapper-content-main-favorite-item-content">
                                        <h2>{{ place?.restaurant?.title }}</h2>
                                        <p>{{ place?.restaurant?.address }}</p>
                                        <p>Средний чек: {{ place?.restaurant?.average_price }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-show="activeTab === 'reviews'"
                                class="profile__wrapper-content-main-reviews profile__wrapper-content-main-scroll active">
                                <div v-for="review in reviews" :key="review.id"
                                    class="profile__wrapper-content-main-reviews-item">
                                    <h2>{{ review.restaurant.title }}</h2>
                                    <div>
                                        <p>{{ review.content }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-show="activeTab === 'images'"
                                class="profile__wrapper-content-main-images profile__wrapper-content-main-scroll active">
                                <label @click.prevent="openModalAddImage">
                                    <div>
                                        <div>
                                            <img :src="Upload" alt="Upload">
                                        </div>
                                        <p>Загрузить файл</p>
                                    </div>
                                </label>
                                <div v-for="(image, index) in userImages" :key="index">
                                    <img :src="image.src" alt="Фото заведения" />
                                </div>
                            </div>
                            <div v-show="activeTab === 'places'"
                                class="profile__wrapper-content-main-places profile__wrapper-content-main-scroll active">
                                <h2>Места, которые вы уже посещали!</h2>
                                <div class="profile__wrapper-content-main-places-content">
                                    <div v-for="visitedItem in visited" :key="visitedItem.id"
                                        class="profile__wrapper-content-main-places-content-item">
                                        <div class="profile__wrapper-content-main-places-content-item-img">
                                            <img :src="visitedItem.images[0].src ? visitedItem.images[0].src : ProfileShopTest"
                                                alt="ProfileShopTest">
                                        </div>
                                        <div class="profile__wrapper-content-main-places-content-item-txt">
                                            <div>
                                                <h3>{{ visitedItem?.title }}</h3>
                                                <p>{{ visitedItem?.description }}</p>
                                            </div>
                                            <ul>
                                                <li>{{ visitedItem?.address }}</li>
                                                <li>Средний чек: {{ visitedItem?.average_price }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile__wrapper-img">
                    <img :src="ProfileImg2" alt="ProfileImg2">
                </div>
            </section>

            <div v-if="showModal" class="profile-edit-modal default-modal">
                <div class="default-modal__wrapper">
                    <div class="modal-header">
                        <div>
                            <img :src="Logo" alt="Logo">
                        </div>
                        <p @click="closeModal">Отмена</p>
                    </div>
                    <form @submit.prevent="updateProfile" enctype="multipart/form-data"
                        class="profile-edit-modal__wrapper-form">
                        <input type="file" @change="onFileChange" id="profile-edit-modal__wrapper-form-file">
                        <label for="profile-edit-modal__wrapper-form-file">
                            <div>
                                <div>
                                    <img :src="previewImage ? previewImage : Upload" alt="Upload">
                                </div>
                                <p>Загрузить файл</p>
                            </div>
                        </label>
                        <div class="profile-edit-modal__wrapper-form-content">
                            <div>
                                <label for="name">Имя</label>
                                <input type="text" id="name" v-model="name" placeholder="Имя пользователя" required>
                            </div>
                            <div>
                                <label for="surname">Фамилия</label>
                                <input type="text" id="surname" v-model="surname" placeholder="Фамилия пользователя"
                                    required>
                            </div>
                            <div class="two">
                                <div>
                                    <label for="date">Дата рождения</label>
                                    <input type="date" id="date" v-model="date" required>
                                </div>
                                <div>
                                    <label for="sex">Пол</label>
                                    <select v-model="sex" name="sex" id="sex">
                                        <option value="Выбрать" selected disabled>Выбрать</option>
                                        <option value="male">Муж</option>
                                        <option value="female">Жен</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>

            <div v-if="showModalAddImage" class="profile-add-modal default-modal">
                <form @submit.prevent="uploadUserImage" enctype="multipart/form-data" class="default-modal__wrapper">
                    <div class="modal-header">
                        <div>
                            <img :src="Logo" alt="Logo">
                        </div>
                        <p @click="closeModalAddImage">Отмена</p>
                    </div>
                    <div class="profile-add-modal-form">
                        <input type="file" @change="onFileChangeImagesUser" id="profile-add-modal-form-file">
                        <label for="profile-add-modal-form-file">
                            <div>
                                <div>
                                    <img :src="Upload" alt="Upload">
                                </div>
                                <p>Загрузить файл</p>
                            </div>
                        </label>
                        <div class="profile-add-modal-form-content">
                            <label for="">Добавить описание</label>
                            <textarea v-model="imageDescription"
                                placeholder="Расскажите о месте, где было сделано ваше фото"></textarea>
                            <button>Добавить фото</button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </AppLayout>
</template>