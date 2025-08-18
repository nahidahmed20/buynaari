<script>
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

export default {
  components: { AuthenticatedLayout, Link },
  props: {
    permission: Object
  },
  data() {
    return {
      form: {
        name: this.permission.name || ''
      },
      errors: {}
    }
  },
  methods: {
    update() {
      router.put(route('permissions.update', this.permission.id), this.form, {
        onError: (errors) => {
          this.errors = errors
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
                <i class="bi bi-pencil-fill me-2"></i> Edit Permission
              </h4>
            </div>

            <!-- Body -->
            <div class="card-body p-4">
              <form @submit.prevent="update">
                
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
                    <i class="bi bi-save me-1"></i> Update Permission
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
  font-size: 16px;
}
</style>
