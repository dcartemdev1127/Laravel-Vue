<script lang="ts" setup>
import TopBar from "@/components/layouts/topBar/index.vue";
import { useLayoutStore } from "@/store/app";
import { computed } from "vue";
import { LAYOUTS } from "@/app/const";
const state = useLayoutStore();

const isHorizontal = computed(() => {
  return state.layoutType === LAYOUTS.HORIZONTAL;
});
</script>
<template>
  <div data-simplebar>
    <div class="header">
      <div class="header-item">
        <a class="header-title">Thousand Oaks</a>
        <div>
          <v-icon class="header-icon">ph-user-circle-thin</v-icon>
          <v-icon class="header-icon">ph-question-thin</v-icon>
        </div>
      </div>
    </div>
    <router-view />
  </div>
</template>

<style>
.header {
  min-height: 60px;
  height: 60px;
  background-color: #F5F5F5;
  padding-top: .5rem;
  padding-bottom: .5rem;
  display: flex;
  align-items: center;
}
.header-item {
  padding-right: 1rem;
  padding-left: 1rem;
  width: 100%;
  display: flex;
  align-items: center;
  margin-left: auto;
  margin-right: auto;
  justify-content: space-between;
}
.header-title {
  text-decoration: none;
  color: #383838 !important;
  fill: #383838 !important;
  stroke: #383838 !important;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-size: 1.56rem;
  line-height: 31px;
  font-weight: 700;
}
.header-icon {
  margin-right: 1rem;
  color: #383838 !important;
  fill: #383838 !important;
  stroke: #383838 !important;
  cursor: pointer;
  font-size: 30px !important;
  font-weight: 500;
}
</style>
