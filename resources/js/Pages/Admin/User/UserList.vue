<script>
  import { usePage, router, Link } from '@inertiajs/vue3'
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

  export default {
    components: {
      AuthenticatedLayout,
      Link
    },
    data() {
      return {
        users: usePage().props.users, 
        search: '',
        currentPage: 1,
        itemsPerPage: 10
      }
    },
    computed: {
      filteredUsers() {
        return this.users.filter(user =>(user.name + ' ' + user.email).toLowerCase().includes(this.search.toLowerCase())
        )
      },
      paginatedUsers() {
        const start = (this.currentPage - 1) * this.itemsPerPage
        return this.filteredUsers.slice(start, start + this.itemsPerPage)
      },
      totalPages() {
        return Math.ceil(this.filteredUsers.length / this.itemsPerPage)
      }
    },
    methods: {
      deleteUser(id) {
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel',
        }).then((result) => {
          if (result.isConfirmed) {
              router.delete(route('users.destroy', id), {
              onSuccess: () => {
                Swal.fire('Deleted!', 'User has been deleted.', 'success')
                router.visit(route('users.index'))
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
            <h4 class="fw-bold mb-0">User List</h4>
          </div>
          <div class="col-12 col-md-3">
            <input 
              v-model="search" 
              type="text" 
              class="form-control" 
              placeholder="🔍 Search users..." 
            />
          </div>
          <div class="col-12 col-md-3 d-flex justify-content-md-end justify-content-center">
            <Link
              :href="route('users.create')"
              class="btn btn-gradient btn-sm text-white w-100 w-md-auto"
              style="background: linear-gradient(135deg, #4caf50 0%, #81c784 100%); 
                     box-shadow: 0 4px 6px rgba(0,0,0,0.15); 
                     font-weight: 500; border-radius: 4px; padding: 8px 20px; transition: all 0.3s ease;"
            >
              <i class="bi bi-plus-lg"></i> Create User
            </Link>
          </div>
        </div>



        <div class="table-responsive shadow-sm rounded">
          <table class="table table-hover align-middle mb-0">
            <thead class="table bg-primary  text-uppercase small">
              <tr>
                <th style="color:black">#</th>
                <th style="color:black"> Name</th>
                <th style="color:black">Eamil</th>
                <th class="text-center" style="color:black">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(user, index) in paginatedUsers" :key="user.id">
                <td>{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td class="text-center">
                  <!-- Edit using Inertia Link -->
                  <Link
                    :href="route('users.edit',user.id)"
                    class="btn btn-sm btn-warning me-2 text-white"
                  >
                    ✏ Edit
                  </Link>

                  <!-- Delete button -->
                  <button class="btn btn-sm btn-danger text-white" @click="deleteUser(user.id)">
                    🗑 Delete
                  </button>
                </td>
              </tr>

              <tr v-if="filteredUsers.length === 0">
                <td colspan="5" class="text-center text-muted py-3">
                  No users found.
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

            <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: currentPage === page }" @click="currentPage = page">
              <a class="page-link" href="#">{{ page }}</a>
            </li>

            <li class="page-item" :class="{ disabled: currentPage === totalPages }" @click="currentPage < totalPages && currentPage++">
              <a class="page-link" href="#">»</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
