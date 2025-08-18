<script>
import { usePage, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

export default {
  components: { AuthenticatedLayout, Link },
  data() {
    return {
      roles: usePage().props.roles,
      search: '',
      currentPage: 1,
      itemsPerPage: 10
    }
  },
  computed: {
    filteredRoles() {
      return this.roles.filter(role =>
        (role.name + ' ' + (role.permissions?.map(p => p.name).join(' ') ?? ''))
          .toLowerCase()
          .includes(this.search.toLowerCase())
      )
    },
    paginatedRoles() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      return this.filteredRoles.slice(start, start + this.itemsPerPage)
    },
    totalPages() {
      return Math.ceil(this.filteredRoles.length / this.itemsPerPage)
    }
  },
  methods: {
    deleteRole(id) {
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
          router.delete(route('roles.destroy', id), {
            onSuccess: () => {
              Swal.fire('Deleted!', 'Permission has been deleted.', 'success')
              router.visit(route('roles.index'))
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

        <!-- Create Button & Search -->
        <div class="row align-items-center mb-3 g-2">
          <div class="col-12 col-md-6 d-flex justify-content-md-start justify-content-center">
            <h4 class="fw-bold mb-0">Role List</h4>
          </div>
          <div class="col-12 col-md-3">
            <input 
              v-model="search" 
              type="text" 
              class="form-control" 
              placeholder="🔍 Search roles..." 
            />
          </div>
          <div class="col-12 col-md-3 d-flex justify-content-md-end justify-content-center">
            <Link
              :href="route('roles.create')"
              class="btn btn-gradient btn-sm text-white w-100 w-md-auto"
              style="background: linear-gradient(135deg, #4caf50 0%, #81c784 100%); 
                     box-shadow: 0 4px 6px rgba(0,0,0,0.15); 
                     font-weight: 500; border-radius: 4px; padding: 8px 20px; transition: all 0.3s ease;"
            >
              <i class="bi bi-plus-lg"></i> Create Role
            </Link>
          </div>
        </div>

        <!-- Roles Table -->
        <div class="table-responsive shadow-sm rounded">
          <table class="table table-hover align-middle mb-0">
            <thead class="table bg-primary  text-uppercase small">
              <tr>
                <th style="color:black">#</th>
                <th style="color:black">Name</th>
                <th style="color:black">Permissions</th>
                <th class="text-center" style="color:black">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(role, index) in paginatedRoles" :key="role.id">
                <td>{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                <td>{{ role.name }}</td>
                <td>
                  <template v-if="role.permissions.length === 0">
                    <span class="text-muted">No permissions</span>
                  </template>
                  <template v-else>
                    <span 
                      v-for="perm in role.permissions" 
                      :key="perm.id" 
                      class="badge bg-primary me-1 px-2 py-1 rounded-2"
                      style="font-size: 0.8rem;"
                    >
                      {{ perm.name }}
                    </span>
                  </template>
                </td>
                <td class="text-center">
                  <div class="d-flex flex-wrap justify-content-center gap-2">
                    <Link :href="route('roles.edit', role.id)" class="btn btn-sm btn-warning text-white">
                      ✏ Edit
                    </Link>
                    <button 
                      class="btn btn-sm btn-danger text-white" 
                      @click="deleteRole(role.id)"
                    >
                      🗑 Delete
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredRoles.length === 0">
                <td colspan="4" class="text-center text-muted py-3">
                  No roles found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <nav class="mt-3">
          <ul class="pagination justify-content-center mb-0 flex-wrap">
            <li 
              class="page-item" 
              :class="{ disabled: currentPage === 1 }" 
              @click="currentPage > 1 && currentPage--"
            >
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

<style scoped>
@media (max-width: 576px) {
  table thead {
    display: none;
  }
  table tbody tr {
    display: block;
    margin-bottom: 10px;
    background: #fff;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  }
  table tbody td {
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
  }
  table tbody td::before {
    content: attr(data-label);
    font-weight: bold;
    color: #555;
  }
}
</style>
