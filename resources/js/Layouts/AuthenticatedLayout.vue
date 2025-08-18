<script setup>
import feather from 'feather-icons'
import { onMounted } from 'vue'

import TopbarStart from '@/Components/Admin/TopbarStart.vue'
import ActivityTimeline from '@/Components/Admin/ActivityTimeline.vue'
import RightSidebar from '@/Components/Admin/RightSidebar.vue'
import AppMenuStart from '@/Components/Admin/AppMenuStart.vue'

onMounted(() => {
    feather.replace()
})
</script>

<template>
  <div class="wrapper">

    <TopbarStart />
    <ActivityTimeline/>
    <RightSidebar />
    <AppMenuStart />

    <slot />
    
  </div>
</template>
