<script setup>
import { ref, computed } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const categories = ref(usePage().props.categories || [])
const search = ref('')
const currentPage = ref(1)
const itemsPerPage = 10

// Filter categories by search
const filteredCategories = computed(() => {
  if (!search.value) return categories.value
  return categories.value.filter(c =>
    (c.title + ' ' + (c.meta_title ?? '') + ' ' + (c.meta_keywords ?? ''))
      .toLowerCase()
      .includes(search.value.toLowerCase())
  )
})

// Paginated categories
const paginatedCategories = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredCategories.value.slice(start, end)
})

// Total pages based on filteredCategories
const totalPages = computed(() => Math.ceil(filteredCategories.value.length / itemsPerPage))

// Delete category
const deleteCategory = (id) => {
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
        onSuccess: (page) => {
          categories.value = categories.value.filter(c => c.id !== id)
          if (page.props.flash.success) Swal.fire('Deleted!', page.props.flash.success, 'success')
        }
      })
    }
  })
}

// Go to specific page
const goToPage = (pageNum) => {
  if (pageNum >= 1 && pageNum <= totalPages.value) currentPage.value = pageNum
}
</script>

<template>
<AuthenticatedLayout>
  <div class="page-content py-4">
    <div class="container">

      <!-- Header & Search -->
      <div class="row align-items-center mb-3 g-2">
        <div class="col-md-6">
          <h4 class="fw-bold mb-0">Category List</h4>
        </div>
        <div class="col-md-3">
          <input v-model="search" type="text" class="form-control" placeholder="🔍 Search categories..." />
        </div>
        <div class="col-md-3 text-md-end">
          <Link :href="route('categories.create')" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Create Category
          </Link>
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
          <thead class="table bg-primary text-uppercase small">
            <tr>
              <th>#</th>
              <th>Title</th>
              <th class="text-center">Stock</th>
              <th class="text-center">Created By</th>
              <th class="text-center">Image</th>
              <th class="text-center">Tags</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(category, index) in paginatedCategories" :key="category.id">
              <td>{{ (currentPage-1)*itemsPerPage + index + 1 }}</td>
              <td>{{ category.title }}</td>
              <td class="text-center">{{ category.stock }}</td>
              <td class="text-center">{{ category.created_by }}</td>
              <td class="text-center">
                <img :src="category.image ? `/storage/${category.image}` : '/backend/assets/images/product/p-1.png'"
                     alt="Category Image" height="80" width="80" class="rounded" />
              </td>
              <td class="text-center">
                <span v-for="(tag, i) in (category.meta_keywords || '').split(',')" :key="i" class="badge bg-success me-1">
                  {{ tag }}
                </span>
              </td>
              <td class="text-center">
                <div class="d-flex justify-content-center gap-2">
                  <Link :href="route('categories.edit', category.id)" class="btn btn-sm btn-warning text-white">✏ Edit</Link>
                  <button class="btn btn-sm btn-danger text-white" @click="deleteCategory(category.id)">🗑 Delete</button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredCategories.length === 0">
              <td colspan="7" class="text-center text-muted py-3">No categories found.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-sm btn-outline-primary me-1" :disabled="currentPage === 1" @click="goToPage(currentPage-1)">Prev</button>
        <button v-for="page in totalPages" :key="page" class="btn btn-sm me-1"
                :class="{'btn-primary': currentPage===page, 'btn-outline-primary': currentPage!==page}"
                @click="goToPage(page)">
          {{ page }}
        </button>
        <button class="btn btn-sm btn-outline-primary" :disabled="currentPage === totalPages" @click="goToPage(currentPage+1)">Next</button>
      </div>

    </div>
  </div>
</AuthenticatedLayout>
</template>
