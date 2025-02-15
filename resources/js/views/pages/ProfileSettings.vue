<script lang="ts" setup>
import { breadcrumb } from "@/components/pages/profileSettings/utils";
import ProfileSettings from "@/components/pages/profileSettings/index.vue";
</script>
<template>
  <Breadcrumb title="Profile Settings" :items="breadcrumb" />
  <ProfileSettings />
</template>
