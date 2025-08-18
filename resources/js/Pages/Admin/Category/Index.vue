<script>
import { usePage, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

export default {
  components: { AuthenticatedLayout, Link },
  data() {
    return {
      categories: usePage().props.categories,
      search: '',
      currentPage: 1,
      itemsPerPage: 10,
      showModal: false,
      selectedCategory: null
    }
  },
  computed: {
    filteredCategories() {
      return this.categories.filter(category =>
        (category.title + ' ' + (category.meta_title ?? '') + ' ' + (category.meta_keywords ?? ''))
          .toLowerCase()
          .includes(this.search.toLowerCase())
      )
    },
    paginatedCategories() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      return this.filteredCategories.slice(start, start + this.itemsPerPage)
    },
    totalPages() {
      return Math.ceil(this.filteredCategories.length / this.itemsPerPage)
    }
  },
  methods: {
    deleteCategory(id) {
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
          router.delete(route('categories.destroy', id), {
            onSuccess: () => {
              Swal.fire('Deleted!', 'Category has been deleted.', 'success')
              router.visit(route('categories.index'))
            }
          })
        }
      })
    },
    showCategory(category) {
      this.selectedCategory = category
      this.showModal = true
    },
    closeModal() {
      this.showModal = false
      this.selectedCategory = null
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
            <h4 class="fw-bold mb-0">Category List</h4>
          </div>
          <div class="col-12 col-md-3">
            <input 
              v-model="search" 
              type="text" 
              class="form-control" 
              placeholder="🔍 Search categories..." 
            />
          </div>
          <div class="col-12 col-md-3 d-flex justify-content-md-end justify-content-center">
            <Link
              :href="route('categories.create')"
              class="btn btn-gradient btn-sm text-white w-100 w-md-auto"
              style="background: linear-gradient(135deg, #4caf50 0%, #81c784 100%); 
                     box-shadow: 0 4px 6px rgba(0,0,0,0.15); 
                     font-weight: 500; border-radius: 4px; padding: 8px 20px; transition: all 0.3s ease;"
            >
              <i class="bi bi-plus-lg"></i> Create Category
            </Link>
          </div>
        </div>

        <!-- Categories Table -->
        <div class="table-responsive shadow-sm rounded">
          <table class="table table-hover align-middle mb-0">
            <thead class="table bg-primary text-uppercase small">
              <tr>
                <th style="color:black">#</th>
                <th style="color:black">Title</th>
                <th style="color:black">Stock</th>
                <th style="color:black">Category By</th>
                <th style="color:black">Image</th>
                <th class="text-center" style="color:black">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(category, index) in paginatedCategories" :key="category.id">
                <td>{{ index + 1 + (currentPage - 1) * itemsPerPage }}</td>
                <td>{{ category.title }}</td>
                <td>{{ category.stock }}</td>
                <td>{{ category.created_by }}</td>
                <td>
                  <img :src="`/${category.image}`" alt="Category Image" height="80" width="80" class="rounded" />
                </td>

                <td class="text-center">
                  <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-sm btn-info text-white" @click="showCategory(category)">
                      👁 Show
                    </button>
                    <Link :href="route('categories.edit', category.id)" class="btn btn-sm btn-warning text-white">
                      ✏ Edit
                    </Link>
                    <button 
                      class="btn btn-sm btn-danger text-white" 
                      @click="deleteCategory(category.id)"
                    >
                      🗑 Delete
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredCategories.length === 0">
                <td colspan="5" class="text-center text-muted py-3">
                  No categories found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <nav class="mt-3" v-if="totalPages > 1">
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

        <!-- Modal for Show -->
        <div class="modal fade" 
            :class="{ show: showModal }" 
            style="display: block;" 
            v-if="showModal">

          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4" 
                style="overflow: hidden;">

              <!-- Header -->
              <div class="modal-header text-white" 
                  style="background: linear-gradient(135deg, #ff7e5f, #feb47b);">
                <h5 class="modal-title fw-bold">
                  <i class="bi bi-grid-fill me-2"></i> Category Details
                </h5>
                <button type="button" 
                        class="btn-close btn-close-white" 
                        @click="closeModal"></button>
              </div>

              <!-- Body -->
              <div class="modal-body p-4" 
                  style="background: linear-gradient(180deg, #ffffff, #f7f9fc);">
                <div class="row g-3">

                  <div class="col-md-6">
                    <p><strong>Title:</strong> {{ selectedCategory.title }}</p>
                    <p><strong>Created By:</strong> {{ selectedCategory.created_by ?? '-' }}</p>
                    <p><strong>Stock:</strong> {{ selectedCategory.stock }}</p>
                    <p><strong>Tag ID:</strong> {{ selectedCategory.tag_id ?? '-' }}</p>
                  </div>

                  <div class="col-md-6">
                    <p><strong>Description:</strong> {{ selectedCategory.description ?? '-' }}</p>
                    <p><strong>Meta Title:</strong> {{ selectedCategory.meta_title ?? '-' }}</p>
                    <p><strong>Meta Keywords:</strong> {{ selectedCategory.meta_keywords ?? '-' }}</p>
                    <p><strong>Meta Description:</strong> {{ selectedCategory.meta_description ?? '-' }}</p>
                  </div>

                  <div v-if="selectedCategory.image" class="col-12 text-center mt-3">
                    <strong>Image:</strong><br>
                    <img :src="`/${selectedCategory.image}`" 
                        alt="Category Image" 
                        class="img-thumbnail shadow-lg" 
                        style="width: 200px; height: 200px; object-fit: cover; border-radius: 12px;" />
                  </div>
                </div>
              </div>

              <!-- Footer -->
              <div class="modal-footer" 
                  style="background: #fafafa;">
                <button class="btn btn-primary border me-2" @click="closeModal">
                  <i class="bi bi-x-circle me-1"></i> Close
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>
