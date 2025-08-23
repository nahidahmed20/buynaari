<script setup>
import { ref, onMounted } from 'vue'
import { useForm, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const subcategory = usePage().props.subcategory || {}
const categories = usePage().props.categories || []
const creators = usePage().props.creators || []

// Tags
const tags = ref([])
const inputValue = ref('')
const tagInput = ref(null)

// Image preview
const imagePreview = ref(subcategory.image ? `/storage/${subcategory.image}` : '/backend/assets/images/product/p-1.png')

// Form
const form = useForm({
  title: subcategory.title || '',
  category_id: subcategory.category_id || '',
  created_by: subcategory.created_by || '',
  stock: subcategory.stock || 0,
  tag_id: subcategory.tag_id || '',
  description: subcategory.description || '',
  meta_title: subcategory.meta_title || '',
  meta_keywords: subcategory.meta_keywords || '',
  meta_description: subcategory.meta_description || '',
  image: null,
  _method: 'put'
})

// Initialize tags
onMounted(() => {
  try {
    const parsed = JSON.parse(subcategory.meta_keywords || '[]')
    if (Array.isArray(parsed)) {
      tags.value = parsed
      form.meta_keywords = JSON.stringify(tags.value)
    }
  } catch {
    tags.value = []
    form.meta_keywords = '[]'
  }
})

// Tags input
const focusInput = () => tagInput.value.focus()
const addTag = () => {
  if (inputValue.value.trim()) {
    tags.value.push(inputValue.value.trim())
    form.meta_keywords = JSON.stringify(tags.value)
    inputValue.value = ''
  }
}
const removeTag = (index) => {
  tags.value.splice(index, 1)
  form.meta_keywords = JSON.stringify(tags.value)
}
const handleKeydown = (event) => {
  if (['Enter', ' ', ','].includes(event.key)) {
    event.preventDefault()
    addTag()
  }
}

// Image
const handleImage = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.image = file
    imagePreview.value = URL.createObjectURL(file)
  } else {
    form.image = null
    imagePreview.value = subcategory.image ? `/storage/${subcategory.image}` : '/backend/assets/images/product/p-1.png'
  }
}

// Submit
const submit = () => {
  form.post(route('subcategories.update', subcategory.id), {
    forceFormData: true,
    onSuccess: () => console.log('Subcategory updated')
  })
}
</script>


<template>
<AuthenticatedLayout>
  <div class="page-content">
    <div class="container-xxl">
      <div class="row">

        <!-- Left Card -->
        <div class="col-xl-3 col-lg-4 mx-auto">
          <div class="card mb-3">
            <div class="card-body text-center bg-light rounded">
              <img :src="imagePreview" class="avatar-xxl" />
              <h5 class="mt-3">Edit Subcategory</h5>
            </div>
          </div>
        </div>

        <!-- Right Card -->
        <div class="col-xl-9 col-lg-8">
          <form @submit.prevent="submit">
            <div class="card">
              <div class="card-header text-white p-4" style="background: linear-gradient(120deg, #2196F3, #4CAF50);">
                <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Subcategory</h4>
              </div>
              <div class="card-body">

                <!-- Image -->
                <div class="mb-4">
                  <label class="form-label fw-bold">Thumbnail Image (optional)</label>
                  <input type="file" class="form-control" @change="handleImage" />
                  <span class="text-danger" v-if="form.errors.image">{{ form.errors.image }}</span>
                </div>

                <!-- General Info -->
                <div class="row g-3 mb-4">
                  <div class="col-lg-6">
                    <label class="form-label">Subcategory Title</label>
                    <input type="text" v-model="form.title" class="form-control" required />
                    <span class="text-danger" v-if="form.errors.title">{{ form.errors.title }}</span>
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Parent Category</label>
                    <select v-model="form.category_id" class="form-select" required>
                      <option value="">Select Category</option>
                      <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.title }}</option>
                    </select>
                    <span class="text-danger" v-if="form.errors.category_id">{{ form.errors.category_id }}</span>
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Created By</label>
                    <select v-model="form.created_by" class="form-select" required>
                      <option value="">Select Creator</option>
                      <option v-for="creator in creators" :key="creator.id" :value="creator.name">{{ creator.name }}</option>
                    </select>
                    <span class="text-danger" v-if="form.errors.created_by">{{ form.errors.created_by }}</span>
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Stock</label>
                    <input type="number" v-model="form.stock" class="form-control" required />
                    <span class="text-danger" v-if="form.errors.stock">{{ form.errors.stock }}</span>
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Tag ID</label>
                    <input type="text" v-model="form.tag_id" class="form-control" />
                  </div>
                  <div class="col-lg-12">
                    <label class="form-label">Description</label>
                    <textarea v-model="form.description" class="form-control" rows="4"></textarea>
                  </div>
                </div>

                <!-- Meta Options -->
                <div class="row g-3 mb-4">
                  <div class="col-lg-6">
                    <label class="form-label">Meta Title</label>
                    <input type="text" v-model="form.meta_title" class="form-control" />
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Meta Keywords</label>
                    <div class="form-control d-flex flex-wrap gap-2" @click="focusInput">
                      <span v-for="(tag, index) in tags" :key="index" class="badge bg-success">
                        {{ tag }}
                        <button type="button" class="btn-close btn-close-white ms-1" @click="removeTag(index)"></button>
                      </span>
                      <input ref="tagInput" v-model="inputValue" class="border-0 flex-grow-1" style="outline: none;" placeholder="Meta Tag" @keydown="handleKeydown" />
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <label class="form-label">Meta Description</label>
                    <textarea v-model="form.meta_description" class="form-control" rows="3"></textarea>
                  </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end">
                  <Link :href="route('subcategories.index')" class="btn btn-danger rounded-pill px-4 me-2 shadow-sm">Cancel</Link>
                  <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="bi bi-save me-1"></i> Update Subcategory
                  </button>
                </div>

              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</AuthenticatedLayout>
</template>

<style scoped>
.avatar-xxl {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 50%;
}
.badge {
  display: inline-flex;
  align-items: center;
  padding: 0.45em 0.65em;
  font-size: 0.85rem;
}
</style>
