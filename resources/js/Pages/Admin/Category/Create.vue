<script setup>
import { useForm, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const creators = usePage().props.creators || []

const form = useForm({
    title: '',
    created_by: '',
    stock: 0,
    tag_id: '',
    description: '',
    meta_title: '',
    meta_keywords: '',
    meta_description: '',
    image: null
})

// For image preview
let imagePreview = null

function handleImage(event) {
    const file = event.target.files[0]
    if (file) {
        form.image = file
        imagePreview.value = URL.createObjectURL(file)
    } else {
        form.image = null
        imagePreview.value = '/backend/assets/images/product/p-1.png'
    }
}

function submit() {
    const formData = new FormData()
    formData.append('title', form.title)
    formData.append('created_by', form.created_by)
    formData.append('stock', form.stock)
    formData.append('tag_id', form.tag_id)
    formData.append('description', form.description)
    formData.append('meta_title', form.meta_title)
    formData.append('meta_keywords', form.meta_keywords)
    formData.append('meta_description', form.meta_description)
    if (form.image) formData.append('image', form.image)

    form.post(route('categories.store'), {
        data: formData,
        onSuccess: () => {
            form.reset()
            imagePreview = null
        }
    })
}
</script>

<template>
<AuthenticatedLayout>
    <div class="page-content">
      <div class="container-xxl">
        <div class="row">
          
          <!-- Left Card (Thumbnail / Info) -->
          <div class="col-xl-3 col-lg-4 mx-auto">
            <div class="card mb-3">
              <div class="card-body text-center bg-light rounded">
                <img 
                  :src="imagePreview || '/backend/assets/images/product/p-1.png'" 
                  class="avatar-xxl" 
                />
                <h5 class="mt-3">Add Category </h5>
              </div>
            </div>
          </div>

          <!-- Right Card (Form) -->
          <div class="col-xl-9 col-lg-8">
            <form @submit.prevent="submit">
                <div class="card">
                    <div class="card-header text-white p-4" style="background: linear-gradient(120deg, #2196F3, #4CAF50);">
                        <h4 class="mb-0 fw-bold"><i class="bi bi-folder-plus me-2"></i> Add Category</h4>
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
                                <label class="form-label">Category Title</label>
                                <input type="text" v-model="form.title" class="form-control" placeholder="Enter Title" required />
                                <span class="text-danger" v-if="form.errors.title">{{ form.errors.title }}</span>
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
                                <input type="number" v-model="form.stock" class="form-control" placeholder="Quantity" required />
                                <span class="text-danger" v-if="form.errors.stock">{{ form.errors.stock }}</span>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Tag ID</label>
                                <input type="text" v-model="form.tag_id" class="form-control" placeholder="#******" />
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
                                <input type="text" v-model="form.meta_title" class="form-control" placeholder="Meta Title" />
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" v-model="form.meta_keywords" class="form-control" placeholder="Meta Tag" />
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">Meta Description</label>
                                <textarea v-model="form.meta_description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end">
                            <Link :href="route('categories.index')" class="btn btn-danger rounded-pill px-4 me-2 shadow-sm">
                                Cancel
                            </Link>
                            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                                <i class="bi bi-save me-1"></i> Save Category
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
</style>
