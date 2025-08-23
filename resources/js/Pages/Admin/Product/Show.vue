<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue'

const { product, categories, subCategories } = defineProps({
    product: Object,
    categories: Array,
    subCategories: Array,
})

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
                            <img :src="'/storage/' + product.thumbnail || '/backend/assets/images/product/p-1.png'" class="avatar-xxl mb-2" />
                            <h5 class="mt-2">{{ product.name }}</h5>
                            <p class="text-muted mb-0">{{ product.brand }}</p>
                        </div>
                    </div>

                    <!-- Multiple Images -->
                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">Product Images</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <img v-for="img in product.images" :key="img.id" :src="'/storage/' + img.image"
                                    class="rounded border" style="width: 80px; height: 80px; object-fit: cover" />
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex flex-column gap-2 mt-3">
                        <Link :href="route('products.edit', product.id)" class="btn btn-warning w-100">
                            <i class="bi bi-pencil-square me-1"></i> Edit Product
                        </Link>
                        <Link :href="route('products.index')" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </Link>
                    </div>
                </div>

                <!-- Right Card (Details) -->
                <div class="col-xl-9 col-lg-8">
                    <div class="card">
                        <div class="card-header text-white p-4" style="background: linear-gradient(120deg, #2196F3, #4CAF50);">
                            <h4 class="mb-0 fw-bold">
                                <i class="bi bi-eye me-2"></i> Product Details
                            </h4>
                        </div>

                        <div class="card-body">
                            <!-- General Info -->
                            <div class="row g-3 mb-4">
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold">Category</label>
                                    <p>{{ product.category?.title || '-' }}</p>
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label fw-bold">Subcategory</label>
                                    <p>{{ product.subcategory?.title || '-' }}</p>
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label fw-bold">Weight</label>
                                    <p>{{ product.weight || '-' }}</p>
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label fw-bold">Gender</label>
                                    <p>{{ product.gender || '-' }}</p>
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label fw-bold">Tag Number</label>
                                    <p>{{ product.tag_number || '-' }}</p>
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label fw-bold">Stock</label>
                                    <p>{{ product.stock }}</p>
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label fw-bold">Price</label>
                                    <p>{{ product.price }}</p>
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label fw-bold">Discount</label>
                                    <p>{{ product.discount }}</p>
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label fw-bold">Tax</label>
                                    <p>{{ product.tax }}</p>
                                </div>

                                <div class="col-lg-12">
                                    <label class="form-label fw-bold">Description</label>
                                    <p>{{ product.description || '-' }}</p>
                                </div>
                            </div>

                            <!-- Array fields -->
                            <div class="row g-3 mb-4">
                                <div class="col-lg-4">
                                    <label class="form-label fw-bold">Sizes</label>
                                    <div>
                                        <span v-for="size in product.sizes" :key="size" class="badge bg-info me-1">{{ size }}</span>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label class="form-label fw-bold">Colors</label>
                                    <div>
                                        <span v-for="color in product.colors" :key="color" class="badge bg-warning text-dark me-1">{{ color }}</span>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label class="form-label fw-bold">Tags</label>
                                    <div>
                                        <span v-for="tag in product.tags" :key="tag" class="badge bg-secondary me-1">{{ tag }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Meta -->
                            <div class="row g-3 mb-4">
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold">Meta Title</label>
                                    <p>{{ product.meta_title || '-' }}</p>
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label fw-bold">Meta Keywords</label>
                                    <div>
                                        <span v-for="kw in product.meta_keywords" :key="kw" class="badge bg-success me-1">{{ kw }}</span>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <label class="form-label fw-bold">Meta Description</label>
                                    <p>{{ product.meta_description || '-' }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>
