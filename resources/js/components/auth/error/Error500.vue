<script lang="ts" setup>
import { Img500 } from "@/assets/images/auth/utils";
</script>
<template>
  <div class="h-100 d-flex align-center justify-center">
    <div class="w-100">
      <v-img :src="Img500" height="210" class="mb-5" />
      <v-row justify="center" class="align-center">
        <v-col cols="12" lg="8">
          <v-card-title class="text-center mt-4">
            <h5 class="text-h6 font-weight-bold text-uppercase">
              Internal Server Error
            </h5>
          </v-card-title>
          <v-card-text class="mt-3 text-center">
            <div class="text-muted mt-1 font-weight-regular">
              It will be as simple as Occidental in fact, it will Occidental to
              an English person
            </div>
            <v-btn color="primary" class="mt-5" to="/" height="45">
              <i class="ph-house me-1" /> Back to home
            </v-btn>
          </v-card-text>
        </v-col>
      </v-row>
    </div>
  </div>
</template>
