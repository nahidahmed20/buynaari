<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { computed, ref } from "vue"

// Props
const { categories, subCategories, product } = defineProps({
    categories: Array,
    subCategories: Array,
    product: Object
})

// Form
const form = useForm({
    category_id: product.category_id || '',
    subcategory_id: product.subcategory_id || '',
    name: product.name || '',
    brand: product.brand || '',
    weight: product.weight || '',
    gender: product.gender || '',
    thumbnail: null,
    images: [], // multiple images
    description: product.description || '',
    sizes: Array.isArray(product.sizes) ? product.sizes : JSON.parse(product.sizes || "[]"),
    colors: Array.isArray(product.colors) ? product.colors : JSON.parse(product.colors || "[]"),
    tag_number: product.tag_number || '',
    stock: product.stock || 0,
    tags: Array.isArray(product.tags) ? product.tags : JSON.parse(product.tags || "[]"),
    price: product.price || 0,
    discount: product.discount || 0,
    tax: product.tax || 0,
    meta_title: product.meta_title || '',
    meta_keywords: Array.isArray(product.meta_keywords) ? product.meta_keywords : JSON.parse(product.meta_keywords || "[]"),
    meta_description: product.meta_description || '',
    _method: 'put'
})

// Subcategory Filter
const filteredSubcategories = computed(() =>
    subCategories.filter(sub => sub.category_id == form.category_id)
)

// Array fields helper
const inputValues = ref({
    sizes: "",
    colors: "",
    tags: "",
    meta_keywords: ""
})

const addTag = (field) => {
    const value = inputValues.value[field].trim()
    if (value) {
        form[field].push(value)
        inputValues.value[field] = ""
    }
}
const removeTag = (field, index) => {
    form[field].splice(index, 1)
}
const handleKeydown = (field, e) => {
    if (["Enter", " ", ","].includes(e.key)) {
        e.preventDefault()
        addTag(field)
    }
    if (e.key === "Backspace" && !inputValues.value[field] && form[field].length) {
        form[field].pop()
    }
}

// Thumbnail preview
const imagePreview = ref(product.thumbnail_url || null)
const handleThumbnail = (e) => {
    const file = e.target.files[0]
    if (file) {
        form.thumbnail = file
        imagePreview.value = URL.createObjectURL(file)
    }
}

// Multiple images preview
const multiplePreviews = ref(product.images_urls || []) // Assuming you pass images_urls from backend
const handleMultipleImages = (e) => {
    form.images = Array.from(e.target.files)
    multiplePreviews.value = form.images.map(file => URL.createObjectURL(file))
}

// Submit
function submit() {
    form.post(route('products.update', product.id))
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
                            <img :src="imagePreview || '/backend/assets/images/product/p-1.png'" class="avatar-xxl" />
                            <h5 class="mt-3">Edit Product </h5>
                        </div>
                    </div>
                </div>

                <!-- Right Card (Form) -->
                <div class="col-xl-9 col-lg-8">
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-header text-white p-4"
                                 style="background: linear-gradient(120deg, #2196F3, #4CAF50);">
                                <h4 class="mb-0 fw-bold">
                                    <i class="bi bi-pencil-square me-2"></i> Edit Product
                                </h4>
                            </div>

                            <div class="card-body">
                                <!-- Thumbnail -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Thumbnail Image</label>
                                    <input type="file" class="form-control" @change="handleThumbnail" />
                                    <div v-if="product.thumbnail" class="d-flex flex-wrap gap-2 mt-2">
                                        <img :src="'/storage/' +  product.thumbnail" class="rounded border" style="width: 80px; height: 80px; object-fit: cover" />
                                    </div>
                                    <div v-if="imagePreview" class="d-flex flex-wrap gap-2 mt-2">
                                        <img :src="imagePreview" class="rounded border" style="width: 80px; height: 80px; object-fit: cover" />
                                    </div>
                                    <span class="text-danger" v-if="form.errors.thumbnail">{{ form.errors.thumbnail }}</span>
                                </div>

                                <!-- Multiple Images -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Product Images</label>
                                    <input type="file" class="form-control" multiple @change="handleMultipleImages" />
                                    <div v-if="product.images" class="d-flex flex-wrap gap-2 mt-2">
                                        <img v-for="(img, index) in product.images" :key="index" :src="'/storage/' + img"
                                             class="rounded border" style="width: 80px; height: 80px; object-fit: cover" />
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        <img v-for="(img, index) in multiplePreviews" :key="index" :src="img"
                                             class="rounded border" style="width: 80px; height: 80px; object-fit: cover" />
                                    </div>
                                </div>

                                <!-- General Info -->
                                <div class="row g-3 mb-4">
                                    <div class="col-lg-6">
                                        <label class="form-label">Category</label>
                                        <select v-model="form.category_id" class="form-select" required>
                                            <option value="">Select Category</option>
                                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.title }}</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label">Subcategory</label>
                                        <select v-model="form.subcategory_id" class="form-select">
                                            <option value="">Select Subcategory</option>
                                            <option v-for="sub in filteredSubcategories" :key="sub.id" :value="sub.id">{{ sub.title }}</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" v-model="form.name" class="form-control" placeholder="Enter product name" required />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label">Brand</label>
                                        <input type="text" v-model="form.brand" class="form-control" placeholder="Brand name" />
                                    </div>

                                    <div class="col-lg-4">
                                        <label class="form-label">Weight</label>
                                        <input type="text" v-model="form.weight" class="form-control" placeholder="e.g. 500g" />
                                    </div>

                                    <div class="col-lg-4">
                                        <label class="form-label">Gender</label>
                                        <select v-model="form.gender" class="form-select">
                                            <option value="">Select</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="unisex">Unisex</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-4">
                                        <label class="form-label">Tag Number</label>
                                        <input type="text" v-model="form.tag_number" class="form-control" placeholder="#******" />
                                    </div>

                                    <div class="col-lg-4">
                                        <label class="form-label">Stock</label>
                                        <input type="number" v-model="form.stock" class="form-control" placeholder="0" />
                                    </div>

                                    <div class="col-lg-4">
                                        <label class="form-label">Price</label>
                                        <input type="number" step="0.01" v-model="form.price" class="form-control" placeholder="0.00" />
                                    </div>

                                    <div class="col-lg-4">
                                        <label class="form-label">Discount</label>
                                        <input type="number" step="0.01" v-model="form.discount" class="form-control" placeholder="0.00" />
                                    </div>

                                    <div class="col-lg-4">
                                        <label class="form-label">Tax</label>
                                        <input type="number" step="0.01" v-model="form.tax" class="form-control" placeholder="0.00" />
                                    </div>

                                    <div class="col-lg-12">
                                        <label class="form-label">Description</label>
                                        <textarea v-model="form.description" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>

                                <!-- Array fields (sizes, colors, tags) -->
                                <div class="row g-3 mb-4">
                                    <div class="col-lg-6">
                                        <label class="form-label">Sizes</label>
                                        <div class="form-control d-flex flex-wrap gap-2">
                                            <span v-for="(size, index) in form.sizes" :key="index" class="badge bg-info">
                                                {{ size }}
                                                <button type="button" class="btn-close btn-close-white ms-1" @click="removeTag('sizes', index)"></button>
                                            </span>
                                            <input type="text" v-model="inputValues.sizes" class="border-0 flex-grow-1" style="outline: none;" placeholder="Add size" @keydown="handleKeydown('sizes', $event)" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label">Colors</label>
                                        <div class="form-control d-flex flex-wrap gap-2">
                                            <span v-for="(color, index) in form.colors" :key="index" class="badge bg-warning text-dark">
                                                {{ color }}
                                                <button type="button" class="btn-close ms-1" @click="removeTag('colors', index)"></button>
                                            </span>
                                            <input type="text" v-model="inputValues.colors" class="border-0 flex-grow-1" style="outline: none;" placeholder="Add color" @keydown="handleKeydown('colors', $event)" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label">Tags</label>
                                        <div class="form-control d-flex flex-wrap gap-2">
                                            <span v-for="(tag, index) in form.tags" :key="index" class="badge bg-secondary">
                                                {{ tag }}
                                                <button type="button" class="btn-close btn-close-white ms-1" @click="removeTag('tags', index)"></button>
                                            </span>
                                            <input type="text" v-model="inputValues.tags" class="border-0 flex-grow-1" style="outline: none;" placeholder="Add tag" @keydown="handleKeydown('tags', $event)" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Meta fields -->
                                <div class="row g-3 mb-4">
                                    <div class="col-lg-6">
                                        <label class="form-label">Meta Title</label>
                                        <input type="text" v-model="form.meta_title" class="form-control" placeholder="Meta Title" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label">Meta Keywords</label>
                                        <div class="form-control d-flex flex-wrap gap-2">
                                            <span v-for="(kw, index) in form.meta_keywords" :key="index" class="badge bg-success">
                                                {{ kw }}
                                                <button type="button" class="btn-close btn-close-white ms-1" @click="removeTag('meta_keywords', index)"></button>
                                            </span>
                                            <input type="text" v-model="inputValues.meta_keywords" class="border-0 flex-grow-1" style="outline: none;" placeholder="Add keyword" @keydown="handleKeydown('meta_keywords', $event)" />
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <label class="form-label">Meta Description</label>
                                        <textarea v-model="form.meta_description" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex justify-content-end">
                                    <Link :href="route('products.index')" class="btn btn-danger rounded-pill px-4 me-2 shadow-sm">Cancel</Link>
                                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm" :disabled="form.processing">
                                        <i class="bi bi-save me-1"></i> Update Product
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
