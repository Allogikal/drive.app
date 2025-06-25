<script setup>
import AppLayout from '@/layouts/AppLayoutHeaderSimple.vue'
import { ref } from 'vue'
import Swal from 'sweetalert2'
import axios from 'axios'
// IMPORT LOCALE IMAGES
import LoginImg1 from '@/assets/images/login-img-1.png'
import LoginImg2 from '@/assets/images/login-img-2.png'

const loginInput = ref('')
const passwordInput = ref('')

const submitForm = async () => {
    try {
        const response = await axios.post(route('login'), {
            login: loginInput.value,
            password: passwordInput.value,
        })
        if (response.status === 200) {
            if (response.data.isAdmin) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Вы вошли в панель управления!",
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(() => {
                    window.location.href = route('admin-applications')
                }, 1600)

                return true
            }
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Вы вошли в систему!",
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(() => {
                window.location.href = route('profile')
            }, 1600)
        }
    } catch (error) {
        if (error.response?.status === 422) {
            let errors = Object.values(error.response.data.errors).flat().join('\n')
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: error.response.data.message || 'Ошибка авторизации',
                text: errors,
                showConfirmButton: false,
                timer: 3000
            })
        } else {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Произошла ошибка. Попробуйте позже.",
                showConfirmButton: false,
                timer: 1500
            })
        }
    }
}
</script>

<template>
    <AppLayout>
        <section class="login login-back">
            <div class="login__img login__img-1">
                <img :src="LoginImg1" alt="login-img-1" />
            </div>
            <div class="wrapper container-small">
                <h1>Авторизация</h1>
                <div class="login__wrapper">
                    <div class="login__wrapper-head">
                        <p>Вы еще не зарегистрированы? Многое упускаете!</p>
                        <a :href="route('register')">Регистрация</a>
                    </div>
                    <form @submit.prevent="submitForm" class="login__wrapper-content">
                        <div>
                            <label for="login">Логин</label>
                            <input v-model="loginInput" name="login" id="login" autocomplete="off" type="text" required
                                placeholder="Логин" />
                        </div>
                        <div>
                            <label for="password">Пароль</label>
                            <input v-model="passwordInput" name="password" id="password" autocomplete="off"
                                type="password" required placeholder="Пароль" />
                        </div>
                        <button type="submit">Войти</button>
                    </form>
                </div>
            </div>
            <div class="login__img login__img-2">
                <img :src="LoginImg2" alt="login-img-2" />
            </div>
        </section>
    </AppLayout>
</template>