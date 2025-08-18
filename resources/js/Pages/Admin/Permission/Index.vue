<script>
import { usePage, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

export default {
  components: {
    AuthenticatedLayout,
    Link
  },
  data() {
    return {
      permissions: usePage().props.permissions,
      search: '',
      currentPage: 1,
      itemsPerPage: 10
    }
  },
  computed: {
    filteredPermissions() {
      return this.permissions.filter(permission =>
        permission.name.toLowerCase().includes(this.search.toLowerCase())
      )
    },
    paginatedPermissions() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      return this.filteredPermissions.slice(start, start + this.itemsPerPage)
    },
    totalPages() {
      return Math.ceil(this.filteredPermissions.length / this.itemsPerPage)
    }
  },
  methods: {
    deletePermission(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          router.delete(route('permissions.destroy', id), {
            onSuccess: () => {
              Swal.fire('Deleted!', 'Permission has been deleted.', 'success')
              router.visit(route('permissions.index'))
            }
          })
        }
      })
    }
  }
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="page-content py-4">

      <div class="container">

        <div class="row align-items-center mb-3 g-2">
          <div class="col-12 col-md-6 d-flex justify-content-md-start justify-content-center">
            <h4 class="fw-bold mb-0">Permissions List</h4>
          </div>
          <div class="col-12 col-md-3">
            <input 
              v-model="search" 
              type="text" 
              class="form-control" 
              placeholder="🔍 Search permissions..." 
            />
          </div>
          <div class="col-12 col-md-3 d-flex justify-content-md-end justify-content-center">
            <Link
              :href="route('permissions.create')"
              class="btn btn-gradient btn-sm text-white w-100 w-md-auto"
              style="background: linear-gradient(135deg, #4caf50 0%, #81c784 100%); 
                     box-shadow: 0 4px 6px rgba(0,0,0,0.15); 
                     font-weight: 500; border-radius: 4px; padding: 8px 20px; transition: all 0.3s ease;"
            >
              <i class="bi bi-plus-lg"></i> Create Permission
            </Link>
          </div>
        </div>

        <div class="table-responsive shadow-sm rounded">
          <table class="table table-hover align-middle mb-0">
            <thead class="table bg-primary  text-uppercase small">
              <tr>
                <th style="color:black">#</th>
                <th style="color:black">Name</th>
                <th class="text-center" style="color:black">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(permission, index) in paginatedPermissions" :key="permission.id">
                <td>{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                <td>{{ permission.name }}</td>
                <td class="text-center">
                  <Link
                    :href="route('permissions.edit', permission.id)"
                    class="btn btn-sm btn-warning me-2 text-white"
                  >
                    ✏ Edit
                  </Link>
                  <button class="btn btn-sm btn-danger text-white" @click="deletePermission(permission.id)">
                    🗑 Delete
                  </button>
                </td>
              </tr>

              <tr v-if="filteredPermissions.length === 0">
                <td colspan="3" class="text-center text-muted py-3">
                  No permissions found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <nav class="mt-3">
          <ul class="pagination justify-content-center mb-0">
            <li class="page-item" :class="{ disabled: currentPage === 1 }" @click="currentPage > 1 && currentPage--">
              <a class="page-link" href="#">«</a>
            </li>

            <li
              class="page-item"
              v-for="page in totalPages"
              :key="page"
              :class="{ active: currentPage === page }"
              @click="currentPage = page"
            >
              <a class="page-link" href="#">{{ page }}</a>
            </li>

            <li
              class="page-item"
              :class="{ disabled: currentPage === totalPages }"
              @click="currentPage < totalPages && currentPage++"
            >
              <a class="page-link" href="#">»</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
