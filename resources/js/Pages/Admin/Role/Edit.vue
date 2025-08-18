<script setup>
import { usePage, useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props from Laravel
const role = usePage().props.role
const allPermissions = usePage().props.permissions

// Form with existing role data
const form = useForm({
  name: role.name || '',
  permissions: role.permissions ? role.permissions.map(p => p.name) : []
})

// Submit function
function submit() {
  form.put(route('roles.update', role.id), {
    preserveScroll: true,
    onSuccess: () => {
      // Optional: toast or redirect logic
    }
  })
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
                 style="background: linear-gradient(120deg, #FF9800, #F44336);">
              <h4 class="mb-0 fw-bold">
                <i class="bi bi-pencil-square me-2"></i> Edit Role
              </h4>
            </div>

            <!-- Body -->
            <div class="card-body p-4">
              <form @submit.prevent="submit">
                
                <!-- Role Name -->
                <div class="mb-4">
                  <label class="form-label fw-bold">Role Name</label>
                  <input 
                    v-model="form.name" 
                    type="text" 
                    class="form-control form-control-lg rounded-pill shadow-sm" 
                    placeholder="Enter role name"
                    required 
                  />
                  <span class="text-danger" v-if="form.errors.name">{{ form.errors.name }}</span>
                </div>

                <!-- Permissions -->
                <div class="mb-4">
                  <label class="form-label fw-bold">Permissions</label>
                  <div class="d-flex flex-wrap gap-2">
                    <label 
                      v-for="perm in allPermissions" 
                      :key="perm.id"
                      class="perm-item"
                      :class="{ 'perm-selected': form.permissions.includes(perm.name) }"
                    >
                      <input 
                        type="checkbox" 
                        class="form-check-input me-2"
                        :value="perm.name" 
                        v-model="form.permissions" 
                      />
                      {{ perm.name }}
                    </label>
                  </div>
                  <span class="text-danger" v-if="form.errors.permissions">{{ form.errors.permissions }}</span>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end">
                  <Link :href="route('roles.index')" 
                        class="btn btn-danger rounded-pill px-4 me-2 shadow-sm">
                    Cancel
                  </Link>
                  <button type="submit" 
                          class="btn btn-warning text-white rounded-pill px-4 shadow-sm">
                    <i class="bi bi-save me-1"></i> Update Role
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
.perm-item {
  display: flex;
  align-items: center;
  padding: 8px 16px;
  border-radius: 20px;
  background-color: #f1f1f1;
  font-size: 14px;
  cursor: pointer;
  transition: 0.3s ease;
  border: 1px solid transparent;
  user-select: none;
}
.perm-item:hover {
  background-color: #fff3e0;
}
.perm-selected {
  background-color: #FF9800 !important;
  color: white;
  border-color: #F57C00;
}
.form-check-input {
  cursor: pointer;
}
</style>
