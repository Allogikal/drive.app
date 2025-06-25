<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import AppLayout from '@/layouts/AppLayoutHeaderSimple.vue'
import Swal from 'sweetalert2'

// IMPORT LOCALE IMAGES
import LoginImg1 from '@/assets/images/login-img-1.png'
import LoginImg2 from '@/assets/images/login-img-2.png'

const name = ref('')
const surname = ref('')
const login = ref('')
const date = ref('')
const sex = ref('')
const password = ref('')
const password_confirmation = ref('')
const captchaInput = ref('')

const captchaImage = ref('')

const fetchCaptcha = async () => {
    try {
        const response = await axios.get(route('generate-captcha'))
        captchaImage.value = response.data.image
    } catch (error) {
        console.error('Ошибка загрузки капчи:', error)
    }
}

const submitForm = async () => {
    try {
        const birthDate = new Date(date.value)
        const today = new Date()
        let age = today.getFullYear() - birthDate.getFullYear()

        if (
            birthDate.getMonth() > today.getMonth() ||
            (birthDate.getMonth() === today.getMonth() && birthDate.getDate() > today.getDate())
        ) {
            age--
        }

        if (age < 18) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Ошибка!",
                text: "Вам должно быть не менее 18 лет для регистрации.",
                showConfirmButton: false,
                timer: 2000
            })
            return
        }

        const response = await axios.post(route('register'), {
            name: name.value,
            surname: surname.value,
            login: login.value,
            date: date.value,
            sex: sex.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
            captcha: captchaInput.value,
        });

        if (response.data.success) {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: response.data.message,
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(() => {
                window.location.href = response.data.redirect;
            }, 1600);
        }
    } catch (error) {
        if (error.response?.status === 422) {
            let errors = Object.values(error.response.data.errors).flat().join('\n');
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: error.response.data.message,
                text: errors,
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Произошла ошибка. Попробуйте позже.",
                showConfirmButton: false,
                timer: 1500
            });
        }
    }
}

const refreshCaptcha = () => {
    fetchCaptcha()
}

onMounted(() => {
    fetchCaptcha()
})
</script>

<template>
    <AppLayout>
        <section class="login login-back">
            <div class="login__img login__img-1">
                <img :src="LoginImg1" alt="login-img-1" />
            </div>
            <div class="wrapper container-small">
                <h1>Регистрация</h1>
                <div class="login__wrapper">
                    <div class="login__wrapper-head">
                        <p>Уже есть аккаунт?</p>
                        <a :href="route('login')">Войти</a>
                    </div>
                    <form @submit.prevent="submitForm" class="login__wrapper-content">
                        <div class="login__wrapper-content-inputs">
                            <div class="login__wrapper-content-col">
                                <div>
                                    <label for="name">Имя</label>
                                    <input v-model="name" name="name" id="name" autocomplete="off" type="text" required
                                        placeholder="Имя">
                                </div>
                                <div>
                                    <label for="surname">Фамилия</label>
                                    <input v-model="surname" name="surname" id="surname" autocomplete="off" type="text"
                                        required placeholder="Фамилия">
                                </div>
                                <div>
                                    <label for="login">Логин</label>
                                    <input v-model="login" name="login" id="login" autocomplete="off" type="text"
                                        required placeholder="Логин">
                                </div>
                            </div>
                            <div class="login__wrapper-content-col">
                                <div class="custom">
                                    <input v-model="date" type="date" name="date" id="date">
                                    <select v-model="sex" name="sex" id="sex">
                                        <option value="" disabled selected>Пол</option>
                                        <option value="female">Жен</option>
                                        <option value="male">Муж</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="password">Пароль</label>
                                    <input v-model="password" name="password" id="password" autocomplete="off"
                                        type="password" required placeholder="Пароль">
                                </div>
                                <div>
                                    <label for="password_confirmation">Повторите пароль</label>
                                    <input v-model="password_confirmation" id="password_confirmation" autocomplete="off"
                                        name="password_confirmation" type="password" required
                                        placeholder="Повторите пароль">
                                </div>
                            </div>
                        </div>
                        <div class="captcha">
                            <img :src="captchaImage" alt="Captcha" @click="refreshCaptcha" />
                            <input v-model="captchaInput" placeholder="Введите код" required />
                        </div>
                        <div class="login__wrapper-content-checkbox">
                            <input type="checkbox" name="check1" id="check1" required>
                            <label for="check1"></label>
                            <a href="/docs/privacy.pdf" target="_blank">Согласие на обработку персональных
                                данных</a>
                        </div>
                        <button type="submit">Регистрация</button>
                    </form>
                </div>
            </div>
            <div class="login__img login__img-2">
                <img :src="LoginImg2" alt="login-img-2" />
            </div>
        </section>
    </AppLayout>
</template>

<style scoped>
.captcha {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-top: 10px;
}

.captcha img {
    max-width: 150px;
    height: auto;
    cursor: pointer;
}
</style>