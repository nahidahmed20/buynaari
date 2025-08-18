<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Login" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center">
            {{ status }}
        </div>

        <div class="login">
            <img src="/backend/assets/images/login-bg.png" alt="Background" class="login__bg">

            <form @submit.prevent="submit" class="login__form">
                <h1 class="login__title">Login</h1>

                <div class="login__inputs">
                    <!-- Email -->
                    <div class="login__box">
                        <input v-model="form.email"
                            id="email"
                            type="email"
                            placeholder="Email ID"
                            required
                            autofocus
                            autocomplete="username"
                            class="login__input">
                        <i class="ri-mail-fill"></i>
                    </div>
                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                        {{ form.errors.email }}
                    </div>

                    <!-- Password -->
                    <div class="login__box">
                        <input v-model="form.password"
                            id="password"
                            type="password"
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                            class="login__input">
                        <i class="ri-lock-2-fill"></i>
                    </div>
                    <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                        {{ form.errors.password }}
                    </div>
                </div>

                <!-- Remember me + Forgot password -->
                <div class="login__check">
                    <div class="login__check-box">
                        <input v-model="form.remember"
                            type="checkbox"
                            id="user-check"
                            class="login__check-input">
                        <label for="user-check" class="login__check-label">Remember me</label>
                    </div>

                    <Link v-if="canResetPassword"
                        :href="route('password.request')"
                        class="login__forgot">
                        Forgot Password?
                    </Link>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="login__button"
                        :disabled="form.processing">
                    {{ form.processing ? 'Logging in...' : 'Login' }}
                </button>

                <!-- Register link -->
                <div class="login__register">
                    Don't have an account?
                    <Link :href="route('register')">Register</Link>
                </div>
            </form>
        </div>
    </GuestLayout>
    
</template>

<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap");
:root {
  --white-color: hsl(0, 0%, 100%);
  --black-color: hsl(0, 0%, 0%);
  --body-font: "Poppins", sans-serif;
  --h1-font-size: 2rem;
  --normal-font-size: 1rem;
  --small-font-size: .813rem;
}
* { box-sizing: border-box; padding: 0; margin: 0; }
body, input, button {
  font-family: var(--body-font);
  font-size: var(--normal-font-size);
}
a { text-decoration: none; }
img { display: block; max-width: 100%; height: auto; }
.login {
  position: relative;
  height: 100vh;
  display: grid;
  align-items: center;
}
.login__bg {
  position: absolute;
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.login__form {
  position: relative;
  margin-inline: 1.5rem;
  background-color: hsla(0, 0%, 100%, .05);
  border: 2px solid hsla(0, 0%, 100%, .7);
  padding: 2.5rem 1rem;
  color: var(--white-color);
  border-radius: 1rem;
  backdrop-filter: blur(16px);
}
.login__title {
  text-align: center;
  font-size: var(--h1-font-size);
  margin-bottom: 1.25rem;
}
.login__inputs, .login__box { display: grid; }
.login__inputs { row-gap: 1.25rem; margin-bottom: 1rem; }
.login__box {
  grid-template-columns: 1fr max-content;
  column-gap: .75rem;
  align-items: center;
  border: 2px solid hsla(0, 0%, 100%, .7);
  padding-inline: 1.25rem;
  border-radius: 4rem;
}
.login__input, .login__button { border: none; outline: none; }
.login__input {
  width: 100%;
  background: none;
  color: var(--white-color);
  padding-block: 1rem;
}
.login__input::placeholder { color: var(--white-color); }
.login__box i { font-size: 1.25rem; }
.login__check, .login__check-box {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.login__check { margin-bottom: 1rem; font-size: var(--small-font-size); }
.login__check-box { column-gap: .5rem; }
.login__check-input {
  width: 1rem;
  height: 1rem;
  accent-color: var(--white-color);
}
.login__forgot { color: var(--white-color); }
.login__forgot:hover { text-decoration: underline; }
.login__button {
  width: 100%;
  padding: 1rem;
  margin-bottom: 1rem;
  background-color: var(--white-color);
  border-radius: 4rem;
  color: var(--black-color);
  font-weight: 500;
  cursor: pointer;
}
.login__register { font-size: var(--small-font-size); text-align: center; }
.login__register a {
  color: var(--white-color);
  font-weight: 500;
}
.login__register a:hover { text-decoration: underline; }
@media screen and (min-width: 576px) {
  .login { justify-content: center; }
  .login__form { width: 420px; padding-inline: 2.5rem; }
  .login__title { margin-bottom: 2rem; }
}
</style>
