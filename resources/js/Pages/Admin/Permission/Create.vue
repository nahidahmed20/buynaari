<script>
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

export default {
  components: { AuthenticatedLayout, Link },
  data() {
    return {
      form: {
        name: ''
      },
      errors: {}
    }
  },
  methods: {
    submit() {
      router.post(route('permissions.store'), this.form, {
        onError: (errors) => {
          this.errors = errors
        },
        onSuccess: () => {
          this.form.name = ''
        }
      })
    }
  }
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="page-content">
      <div class="container py-4">
        <div class="col-lg-8 mx-auto">
          <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

            <!-- Header -->
            <div class="card-header text-white p-4"
                 style="background: linear-gradient(120deg, #2196F3, #4CAF50);">
              <h4 class="mb-0 fw-bold">
                <i class="bi bi-shield-check me-2"></i> Create Permission
              </h4>
            </div>

            <!-- Body -->
            <div class="card-body p-4">
              <form @submit.prevent="submit">

                <!-- Permission Name -->
                <div class="mb-4">
                  <label class="form-label fw-bold">Permission Name</label>
                  <input
                    v-model="form.name"
                    type="text"
                    class="form-control form-control-lg rounded-pill shadow-sm"
                    placeholder="Enter permission name"
                    required
                  />
                  <span class="text-danger" v-if="errors.name">{{ errors.name }}</span>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end">
                  <Link :href="route('permissions.index')"
                        class="btn btn-danger rounded-pill px-4 me-2 shadow-sm">
                    Cancel
                  </Link>
                  <button type="submit"
                          class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="bi bi-save me-1"></i> Save Permission
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
.form-control {
  transition: all 0.2s ease-in-out;
}
.form-control:focus {
  border-color: #4CAF50;
  box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
}
</style>
