<script setup>
import { Hospital } from 'lucide-vue-next';

</script>

<template>
    <header class="border-b border-blue-400 bg-blue-500 sticky top-0 z-50 ">
      <div class="container mx-auto px-4 py-4 max-w-7xl">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="p-2  rounded-lg">
              <Hospital class="h-6 w-6 text-white" fill="currentColor" />
            </div>
            <div>
              <h1 class="text-xl font-semibold text-white">Hospital Infantil</h1>              
            </div>
          </div>
        </div>
      </div>
    </header>
</template>