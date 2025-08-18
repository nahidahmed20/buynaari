<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

function submit() {
  form.post(route('users.store'), {
    onSuccess: () => form.reset()
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="page-content">
      <div class="container py-4">
        <div class="col-lg-8 mx-auto">

          <!-- Card -->
          <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

            <!-- Header -->
            <div class="card-header text-white p-4"
                 style="background: linear-gradient(120deg, #0288d1, #26c6da);">
              <h4 class="mb-0 fw-bold">
                <i class="bi bi-person-plus-fill me-2"></i> Create Account
              </h4>
            </div>

            <!-- Body -->
            <div class="card-body p-4">
              <form @submit.prevent="submit">
                
                <!-- Name -->
                <div class="mb-4">
                  <label class="form-label fw-bold">Name</label>
                  <input 
                    v-model="form.name" 
                    type="text" 
                    class="form-control form-control-lg rounded-pill shadow-sm" 
                    placeholder="Enter full name" 
                    required
                  />
                  <span class="text-danger" v-if="form.errors.name">{{ form.errors.name }}</span>
                </div>

                <!-- Email -->
                <div class="mb-4">
                  <label class="form-label fw-bold">Email</label>
                  <input 
                    v-model="form.email" 
                    type="email" 
                    class="form-control form-control-lg rounded-pill shadow-sm" 
                    placeholder="Enter email address" 
                    required
                  />
                  <span class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</span>
                </div>

                <!-- Password -->
                <div class="mb-4">
                  <label class="form-label fw-bold">Password</label>
                  <input 
                    v-model="form.password" 
                    type="password" 
                    class="form-control form-control-lg rounded-pill shadow-sm" 
                    placeholder="Enter password" 
                    required
                  />
                  <span class="text-danger" v-if="form.errors.password">{{ form.errors.password }}</span>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                  <label class="form-label fw-bold">Confirm Password</label>
                  <input 
                    v-model="form.password_confirmation" 
                    type="password" 
                    class="form-control form-control-lg rounded-pill shadow-sm" 
                    placeholder="Re-enter password" 
                    required
                  />
                  <span class="text-danger" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</span>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end">
                  <Link :href="route('users.index')" 
                        class="btn btn-danger rounded-pill px-4 me-2 shadow-sm">
                    Cancel
                  </Link>
                  <button type="submit" 
                          class="btn btn-success rounded-pill px-4 shadow-sm"
                          :disabled="form.processing">
                    <i class="bi bi-save me-1"></i> Create Account
                  </button>
                </div>

              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.form-control-lg {
  font-size: 1rem;
  padding: 0.75rem 1.25rem;
}
</style>
