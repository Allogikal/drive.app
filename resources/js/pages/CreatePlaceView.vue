<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayoutHeader.vue';
import Upload from '@/assets/images/upload.svg';
import Swal from 'sweetalert2'

const restaurant = ref({
    title: '',
    phone: '',
    address: '',
    INN: '',
    KPP: '',
    OGRN: '',
    worktime_start: '',
    worktime_end: '',
    telegram_url: '',
    whatsapp_url: '',
    vk_url: '',
    description: ''
});

const files = ref([])

const timeOptions = ref([]);

onMounted(() => {
    generateTimeOptions();
});

const generateTimeOptions = () => {
    const times = [];
    for (let h = 0; h < 24; h++) {
        for (let m = 0; m < 60; m += 30) {
            const hour = h.toString().padStart(2, '0');
            const minute = m.toString().padStart(2, '0');
            times.push(`${hour}:${minute}`);
        }
    }
    timeOptions.value = times;
};

const onFileChange = (event) => {
    const selectedFiles = event.target.files;
    for (let i = 0; i < selectedFiles.length; i++) {
        files.value.push(selectedFiles[i])
    }
}

const removeImage = (index) => {
    files.value.splice(index, 1)
}

const registerRestaurant = async () => {
    if (restaurant.value.worktime_start >= restaurant.value.worktime_end) {
        Swal.fire({
            icon: "error",
            title: "Ошибка",
            text: "Время открытия не может быть позже времени закрытия.",
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }

    try {
        const formData = new FormData();
        for (const [key, value] of Object.entries(restaurant.value)) {
            if (value !== null && value !== undefined) {
                formData.append(key, value);
            }
        }
        for (let i = 0; i < files.value.length; i++) {
            formData.append(`images[${i}]`, files.value[i]);
        }
        await router.post(route('restaurants.store'), formData, {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Заведение успешно зарегистрировано!",
                    showConfirmButton: false,
                    timer: 1500
                });
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
        });
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: "error",
            title: "Произошла ошибка.",
            showConfirmButton: false,
            timer: 1500
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <AppLayout>
        <section class="shop-registr">
            <div class="shop-registr__wrapper container">
                <h1>Регистрация заведения</h1>

                <form @submit.prevent="registerRestaurant" enctype="multipart/form-data">
                    <div class="shop-registr__wrapper-content">
                        <div class="shop-registr__wrapper-content-info">
                            <h2>Информация о заведении</h2>
                            <div class="shop-registr__wrapper-content-info-wrap">
                                <div class="shop-registr__wrapper-content-default">
                                    <label for="">Наименование заведения*</label>
                                    <input v-model="restaurant.title" type="text" required placeholder="Наименование">
                                </div>
                                <div class="shop-registr__wrapper-content-default">
                                    <label for="">Контактный телефон*</label>
                                    <input v-model="restaurant.phone" type="tel" required placeholder="Телефон">
                                </div>
                                <div class="shop-registr__wrapper-content-default">
                                    <label for="">Адрес*</label>
                                    <input v-model="restaurant.address" type="text" required placeholder="Адрес">
                                </div>
                                <div class="shop-registr__wrapper-content-default">
                                    <label for="">ИНН*</label>
                                    <input v-model="restaurant.INN" type="text" required placeholder="ИНН">
                                </div>
                                <div class="shop-registr__wrapper-content-default">
                                    <label for="">КПП*</label>
                                    <input v-model="restaurant.KPP" type="text" required placeholder="КПП">
                                </div>
                                <div class="shop-registr__wrapper-content-default">
                                    <label for="">ОГРН*</label>
                                    <input v-model="restaurant.OGRN" type="text" required placeholder="ОГРН">
                                </div>
                                <div class="shop-registr__wrapper-content-info-wrap-select">
                                    <select v-model="restaurant.worktime_start" required>
                                        <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}
                                        </option>
                                    </select>
                                    <span>–</span>
                                    <select v-model="restaurant.worktime_end" required>
                                        <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}
                                        </option>
                                    </select>
                                    <label for="">Время работы*</label>
                                </div>
                            </div>
                        </div>

                        <div class="shop-registr__wrapper-content-about">
                            <div class="shop-registr__wrapper-content-about-soc">
                                <div class="shop-registr__wrapper-content-about-soc-imgs">
                                    <p>Изображение</p>
                                    <input type="file" multiple id="shop-registr__wrapper-content-about-soc-img"
                                        @change="onFileChange">
                                    <label class="shop-registr__wrapper-content-about-soc-img"
                                        for="shop-registr__wrapper-content-about-soc-img">
                                        <div>
                                            <div>
                                                <img :src="Upload" alt="Upload">
                                            </div>
                                            <p>Загрузить файл</p>
                                        </div>
                                    </label>
                                </div>
                                <div class="shop-registr__wrapper-content-about-soc-links">
                                    <div class="shop-registr__wrapper-content-default">
                                        <label for="">Telegram</label>
                                        <input v-model="restaurant.telegram_url" type="text" placeholder="ссылка">
                                    </div>
                                    <div class="shop-registr__wrapper-content-default">
                                        <label for="">WhatsApp</label>
                                        <input v-model="restaurant.whatsapp_url" type="text" placeholder="ссылка">
                                    </div>
                                    <div class="shop-registr__wrapper-content-default">
                                        <label for="">VK</label>
                                        <input v-model="restaurant.vk_url" type="text" placeholder="ссылка">
                                    </div>
                                </div>
                            </div>
                            <div v-if="files.length > 0" class="uploaded-images">
                                <ul>
                                    <li class="form_p" v-for="(file, index) in files" :key="index">
                                        {{ file.name }}
                                        <button class="form_p" @click="removeImage(index)">Удалить</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="shop-registr__wrapper-content-default">
                                <label for="">Описание заведения</label>
                                <textarea v-model="restaurant.description" placeholder="Описание заведения"></textarea>
                            </div>
                        </div>

                        <div class="shop-registr__wrapper-content-btn">
                            <button type="submit">Создать</button>
                            <button @click.prevent="goBack">Отмена</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </AppLayout>
</template>