<script lang="ts" setup>
import Banner from "@/components/auth/Banner.vue";
import back from '@/assets/images/background.png';

</script>
<template>
  <div 
    style="height: 100vh; background-image: url(''); background-size: cover;" 
    :style="{ backgroundImage: `url(${back})` }"
    class="d-flex align-center auth-wrapper">
    <v-row class="d-flex justify-end">
      <v-col cols="4" class="customLogin">
        <Card class="h-100">
          <router-view></router-view>
        </Card>
      </v-col>
    </v-row>
  </div>
</template>

<style>
.customLogin {
  padding: unset;
  margin-right: 40px;
  box-shadow: gray 1px 1px 10px 2px;
  border-radius: .75rem;
}
</style>
