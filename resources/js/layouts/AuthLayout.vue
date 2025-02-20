<script lang="ts" setup>
import Banner from "@/components/auth/Banner.vue";
import back from '@/assets/images/background.png';

</script>
<template>
  <div 
    style="height: 100vh; background-image: url(''); background-size: cover;" 
    :style="{ backgroundImage: `url(${back})` }"
    class="d-flex align-center auth-wrapper">
    <!-- <v-container style="height: inherit" class="d-flex">
      <v-row justify="center" class="align-center" no-gutters>
        <v-col cols="12" xl="8">
          <v-card>
            <v-row no-gutters>
              <v-col class="d-none d-sm-block" cols="12" lg="5">
                <Banner />
              </v-col>
              <v-col cols="12" lg="7" class="">
                <router-view />
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
    </v-container> -->
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
