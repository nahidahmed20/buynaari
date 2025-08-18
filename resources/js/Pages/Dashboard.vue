<script setup>
import { watch } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import RightContent from '@/Components/Admin/RightContent.vue'
import toastr from 'toastr'
import 'toastr/build/toastr.min.css'

const page = usePage()

watch(
  () => page.props?.value?.flash, // optional chaining to prevent crash
  (flash) => {
    if (flash?.success) {
      toastr.success(flash.success)
    }
    if (flash?.error) {
      toastr.error(flash.error)
    }
  },
  { immediate: true }
)
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <RightContent />
  </AuthenticatedLayout>
</template>
