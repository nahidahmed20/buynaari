<script setup>
import { ref, computed } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const page = usePage()
const products = ref(page.props.products || [])

// Search
const search = ref('')

// Pagination
const perPage = 10
const currentPage = ref(1)
const totalPages = computed(() => Math.ceil(filteredProducts.value.length / perPage))

// Filtered products
const filteredProducts = computed(() => {
    if (!search.value) return products.value
    return products.value.filter(p =>
        p.name.toLowerCase().includes(search.value.toLowerCase()) ||
        (p.category?.title || '').toLowerCase().includes(search.value.toLowerCase()) ||
        (p.subcategory?.title || '').toLowerCase().includes(search.value.toLowerCase())
    )
})

// Paginated products
const paginatedProducts = computed(() => {
    const start = (currentPage.value - 1) * perPage
    const end = start + perPage
    return filteredProducts.value.slice(start, end)
})

// Change page
function goToPage(pageNum) {
    if (pageNum >= 1 && pageNum <= totalPages.value) {
        currentPage.value = pageNum
    }
}

// Delete
function deleteProduct(id) {
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
            router.delete(route('products.destroy', id), {
                onSuccess: () => Swal.fire('Deleted!', 'Product has been deleted.', 'success')
            })
        }
    })
}
</script>

<template>
<AuthenticatedLayout>
  <div class="page-content py-4">
    <div class="container-xxl">

      <!-- Header & Search -->
      <div class="row align-items-center mb-3 g-2">
        <div class="col-md-6">
          <h4 class="fw-bold mb-0">Product List</h4>
        </div>
        <div class="col-md-3">
          <input v-model="search" type="text" class="form-control" placeholder="🔍 Search products..." />
        </div>
        <div class="col-md-3 text-end">
          <Link :href="route('products.create')" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Create Product
          </Link>
        </div>
      </div>

      <!-- Products Table -->
      <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
          <thead class="table bg-primary text-uppercase small text-white">
            <tr>
              <th>#</th>
              <th>Product Name</th>
              <th class="text-center">Category</th>
              <th class="text-center">Sub Category</th>
              <th class="text-center">Stock</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(product, index) in paginatedProducts" :key="product.id">
              <td>{{ (currentPage-1)*perPage + index + 1 }}</td>
              <td>{{ product.name }}</td>
              <td class="text-center">{{ product.category?.title }}</td>
              <td class="text-center">{{ product.subcategory?.title }}</td>
              <td class="text-center">{{ product.stock }}</td>
              <td class="text-center">
                <div class="d-flex justify-content-center gap-2">
                  <Link :href="route('products.show', product.id)" class="btn btn-sm btn-info text-white">👁 Show</Link>
                  <Link :href="route('products.edit', product.id)" class="btn btn-sm btn-warning text-white">✏ Edit</Link>
                  <button class="btn btn-sm btn-danger text-white" @click="deleteProduct(product.id)">🗑 Delete</button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredProducts.length === 0">
              <td colspan="6" class="text-center text-muted py-3">No products found.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-sm btn-outline-primary me-1" :disabled="currentPage === 1" @click="goToPage(currentPage-1)">Prev</button>
        <button v-for="pageNum in totalPages" :key="pageNum" class="btn btn-sm me-1"
                :class="{'btn-primary': currentPage===pageNum, 'btn-outline-primary': currentPage!==pageNum}"
                @click="goToPage(pageNum)">
          {{ pageNum }}
        </button>
        <button class="btn btn-sm btn-outline-primary" :disabled="currentPage === totalPages" @click="goToPage(currentPage+1)">Next</button>
      </div>

    </div>
  </div>
</AuthenticatedLayout>
</template>
