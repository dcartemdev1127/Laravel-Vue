<script lang="ts" setup>
import { Avatar1 } from "@/assets/images/users/utils";
import { portfolio } from "@/components/pages/profileSettings/utils";
</script>
<template>
  <v-card>
    <v-card-text class="text-center">
      <v-avatar size="90" class="border pa-1">
        <v-img :src="Avatar1" class="rounded-circle" />
      </v-avatar>

      <div class="text-subtitle-1 mt-3 mb-1 font-weight-bold">
        Richard Marshall <i class="bx bxs-badge-check text-info bx-xs"></i>
      </div>
      <div class="text-muted">Web Developer</div>
    </v-card-text>

    <v-divider />
    <Card title="Complete Your Profile" elevation="0">
      <template #title-action>
        <v-btn density="compact" color="secondary" variant="tonal" class="px-1">
          <i class="ph-pencil me-1" />
          <span>Edit</span>
        </v-btn>
      </template>
      <v-card-text>
        <v-progress-linear
          :model-value="30"
          color="primary"
          height="15"
          rounded
        >
          <template v-slot:default="{ value }">
            <strong>{{ Math.ceil(value) }}%</strong>
          </template>
        </v-progress-linear>
      </v-card-text>
    </Card>
    <v-divider />
    <Card elevation="0" title="Portfolio">
      <template #title-action>
        <v-btn density="compact" color="info" variant="tonal" class="px-1">
          <i class="ph-plus me-1" />
          Add
        </v-btn>
      </template>
      <v-card-text>
        <v-row
          v-for="(item, i) in portfolio"
          :key="'portfolio-' + i"
          no-gutters
          class="mb-4"
        >
          <v-col cols="auto">
            <v-avatar :color="item.color" variant="tonal" density="compact">
              <i :class="item.icon" />
            </v-avatar>
          </v-col>
          <v-col class="ps-4">
            <div class="simple-border pa-2">{{ item.value }}</div>
          </v-col>
        </v-row>
      </v-card-text>
    </Card>
  </v-card>
</template>
