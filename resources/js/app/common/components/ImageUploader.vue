<script lang="ts" setup>
import { computed, ref, watch } from "vue";
import { v4 as uuidv4 } from "uuid";
import { ImageUploaderType } from "@/app/common/types/imageUploader";
import axios from "@/app/http/axios";

const emit = defineEmits(["update:modelValue"]);

const uploaderItem = ref<ImageUploaderType[]>([]);
const prop = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
  text: {
    type: String,
    default: "Drop files here or click to upload.",
  },
  multiple: {
    type: Boolean,
    default: true,
  },
});

const files = computed({
  get() {
    const existingFiles: ImageUploaderType[] = prop.modelValue.map(
      (data: any, index) => ({
        ...data,
        src: data?.src ?? data,
        name: data?.name ?? `uploaded-img-${index}.png`,
        size: data?.size ?? 1024,
        id: data?.id ?? uuidv4(),
        path: data?.path,
      })
    );
    uploaderItem.value = existingFiles;
    return existingFiles;
  },
  async set(value: any) {
    const processedFiles = await Promise.all(
      value.map(async (file: any) => {
        if (!file.id) {
          const formData = new FormData();
          formData.append("image", file);

          try {
            const response = await axios.post("/api/upload-image", formData, {
              headers: { "Content-Type": "multipart/form-data" },
            });

            if (response?.data?.url) {
              return {
                src: URL.createObjectURL(file),
                name: file.name,
                size: file.size,
                id: uuidv4(),
                path: response.data.url,
              };
            }
          } catch (error) {
            console.error("Upload failed:", error);
          }
        }
        return file;
      })
    );

    // Update the uploaderItem correctly
    if (prop.multiple) {
      uploaderItem.value = [...uploaderItem.value, ...processedFiles];
    } else {
      uploaderItem.value = processedFiles;
    }

    // Emit the updated model value
    emit("update:modelValue", uploaderItem.value);
  },
});

// Watch `modelValue` to keep `uploaderItem` in sync
watch(
  () => prop.modelValue,
  (newValue) => {
    uploaderItem.value = newValue.map((data: any, index) => ({
      ...data,
      src: data?.src ?? data,
      name: data?.name ?? `uploaded-img-${index}.png`,
      size: data?.size ?? 1024,
      id: data?.id ?? uuidv4(),
      path: data?.path,
    }));
  },
  { deep: true, immediate: true }
);

const onRemove = (item: any) => {
  uploaderItem.value = uploaderItem.value.filter((uploaded) => uploaded.id !== item.id);
  emit("update:modelValue", uploaderItem.value);
};
</script>
<template>
  <div class="position-relative" style="z-index: 9999;">
    <v-file-input
      v-model="files"
      :multiple="multiple"
      variant="plain"
      :clearable="false"
      prepend-icon=""
      class="file-uploader"
      :accept="'.png, .jpeg'"
    />

    <div class="file-uploader-content">
      <div class="text-center">
        <i class="ph-cloud-arrow-down ph-4x" />
      </div>
      <div class="text-subtitle-1 font-weight-bold text-center">
        {{ text }}
      </div>
    </div>
  </div>
  <div>
    <v-card
      v-for="(file, index) in uploaderItem"
      :key="'temp-img-' + index"
      class="border mb-3"
      elevation="0"
    >
      <v-card-text class="d-flex align-center justify-space-between">
        <div class="d-flex">
          <v-avatar rounded="lg" size="large" class="pa-2 mx-2" color="light">
            <v-img :src="file.src" />
          </v-avatar>
          <div class="d-flex flex-column">
            <span class="font-weight-bold">
              {{ file.name }}
            </span>
            <span> {{ Math.ceil(Number(file.size) / 1024) }} kb </span>
          </div>
        </div>
        <v-btn size="x-small" color="danger" @click="onRemove(file)">
          Delete
        </v-btn>
      </v-card-text>
    </v-card>
  </div>
</template>