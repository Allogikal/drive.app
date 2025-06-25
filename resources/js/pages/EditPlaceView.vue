<script setup>
import Upload from '@/assets/images/upload.svg'
import Logo from '@/assets/images/logo.png'
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
    place: Object
})

const files = ref([])
const timeOptions = ref([]);

const restaurant = ref({
    title: props.place.title,
    phone: props.place.phone,
    address: props.place.address,
    INN: props.place.INN,
    KPP: props.place.KPP || '',
    OGRN: props.place.OGRN,
    worktime_start: props.place.worktime_start || '',
    worktime_end: props.place.worktime_end || '',
    telegram_url: props.place.telegram_url || '',
    whatsapp_url: props.place.whatsapp_url || '',
    vk_url: props.place.vk_url || '',
    description: props.place.description || ''
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

const updateRestaurant = async () => {
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

        await router.post(route('edit-place.update', props.place.id), formData, {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Заведение отредактировано!",
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

onMounted(() => {
    generateTimeOptions();
});
</script>

<template>
    <div>
        <header class="simple-header">
            <a :href="route('home')"><img :src="Logo" alt="Logo"></a>
            <a href="#" @click.prevent="goBack">Назад</a>
        </header>
        <main>
            <section class="shop-registr">
                <div class="shop-registr__wrapper container">
                    <h1>Редактирование заведения</h1>
                    <form @submit.prevent="updateRestaurant" enctype="multipart/form-data">
                        <div class="shop-registr__wrapper-content">
                            <div class="shop-registr__wrapper-content-info">
                                <h2>Информация о заведении</h2>
                                <div class="shop-registr__wrapper-content-info-wrap">
                                    <div class="shop-registr__wrapper-content-default">
                                        <label for="">Наименование заведения*</label>
                                        <input type="text" required placeholder="Наименование"
                                            v-model="restaurant.title">
                                    </div>
                                    <div class="shop-registr__wrapper-content-default">
                                        <label for="">Контактный телефон*</label>
                                        <input type="tel" required placeholder="Телефон" v-model="restaurant.phone">
                                    </div>
                                    <div class="shop-registr__wrapper-content-default">
                                        <label for="">Адрес*</label>
                                        <input type="text" required placeholder="Адрес" v-model="restaurant.address">
                                    </div>
                                    <div class="shop-registr__wrapper-content-default">
                                        <label for="">ИНН*</label>
                                        <input type="text" required placeholder="ИНН" v-model="restaurant.INN">
                                    </div>
                                    <div class="shop-registr__wrapper-content-default">
                                        <label for="">КПП*</label>
                                        <input type="text" required placeholder="КПП" v-model="restaurant.KPP">
                                    </div>
                                    <div class="shop-registr__wrapper-content-default">
                                        <label for="">ОГРН*</label>
                                        <input type="text" required placeholder="ОГРН" v-model="restaurant.OGRN">
                                    </div>
                                    <div class="shop-registr__wrapper-content-info-wrap-select">
                                        <select name="" id="" required v-model="restaurant.worktime_start">
                                            <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}
                                            </option>
                                        </select>
                                        <span>–</span>
                                        <select name="" id="" required v-model="restaurant.worktime_end">
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
                                            <input type="text" placeholder="ссылка" v-model="restaurant.telegram_url">
                                        </div>
                                        <div class="shop-registr__wrapper-content-default">
                                            <label for="">Whats App</label>
                                            <input type="text" placeholder="ссылка" v-model="restaurant.whatsapp_url">
                                        </div>
                                        <div class="shop-registr__wrapper-content-default">
                                            <label for="">VK</label>
                                            <input type="text" placeholder="ссылка" v-model="restaurant.vk_url">
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
                                    <textarea placeholder="Описание заведения"
                                        v-model="restaurant.description"></textarea>
                                </div>
                            </div>
                            <div class="shop-registr__wrapper-content-btn">
                                <button type="submit">Редактировать</button>
                                <button @click.prevent="goBack">Отмена</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
</template>