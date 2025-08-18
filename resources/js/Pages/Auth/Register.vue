<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="login">
            <img src="/backend/assets/images/login-bg.png" alt="Background" class="login__bg">

            <form @submit.prevent="submit" class="login__form">
                <h1 class="login__title">Register</h1>

                <div class="login__inputs">
                    <!-- Name -->
                    <div class="login__box">
                        <input v-model="form.name"
                               id="name"
                               type="text"
                               placeholder="Full Name"
                               required
                               autofocus
                               autocomplete="name"
                               class="login__input">
                        <i class="ri-user-fill"></i>
                    </div>
                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                        {{ form.errors.name }}
                    </div>

                    <!-- Email -->
                    <div class="login__box">
                        <input v-model="form.email"
                               id="email"
                               type="email"
                               placeholder="Email ID"
                               required
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
                               autocomplete="new-password"
                               class="login__input">
                        <i class="ri-lock-2-fill"></i>
                    </div>
                    <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                        {{ form.errors.password }}
                    </div>

                    <!-- Confirm Password -->
                    <div class="login__box">
                        <input v-model="form.password_confirmation"
                               id="password_confirmation"
                               type="password"
                               placeholder="Confirm Password"
                               required
                               autocomplete="new-password"
                               class="login__input">
                        <i class="ri-lock-password-fill"></i>
                    </div>
                    <div v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">
                        {{ form.errors.password_confirmation }}
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="login__button2"
                        :disabled="form.processing">
                    {{ form.processing ? 'Registering...' : 'Register' }}
                </button>

                <!-- Login link -->
                <div class="login__register">
                    Already have an account?
                    <Link :href="route('login')">Login</Link>
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
  border: 2px solid #fb4b01;
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
.login__button2 {
  width: 100%;
  padding: 1rem;
  margin-bottom: 1rem;
  background-color: var(--white-color);
  border-radius: 4rem;
  color: white;
  font-weight: 500;
  cursor: pointer;
  border: 2px solid #fb4b01;
  background: #fb4b01;
  
  /* Transition for smooth effect */
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login__button2:hover {
  transform: scale(1.05); /* Slightly bigger */
  box-shadow: 0 4px 15px rgba(251, 75, 1, 0.4); /* Nice shadow */
}

.login__inputs, .login__box { display: grid; }
.login__inputs { row-gap: 1.25rem; margin-bottom: 1rem; }
.login__box {
  grid-template-columns: 1fr max-content;
  column-gap: .75rem;
  align-items: center;
  border: 2px solid #fb4b01;
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
