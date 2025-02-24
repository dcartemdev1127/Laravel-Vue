<template>
  <v-row>
    <v-col cols="12">
      <Card>
        <v-card-title class="text-center">
          <h5 class="text-h6 font-weight-bold">Edit {{ field?.label }} Field</h5>
        </v-card-title>
        <v-card-text>
          <div class="font-weight-bold mb-2 mt-3">Label <i class="ph-asterisk ph-xs text-danger" /></div>
          <TextField
            v-model="localField.label"
            hide-details
            placeholder="Enter field label"
          />
          <div class="font-weight-bold mb-2 mt-3">Description <i class="ph-asterisk ph-xs text-danger" /></div>
          <TextField
            v-model="localField.description"
            hide-details
            placeholder="Enter field description"
          />
          <div class="font-weight-bold mb-2 mt-3">Placeholder <i class="ph-asterisk ph-xs text-danger" /></div>
          <TextField
            v-model="localField.placeholder"
            hide-details
            placeholder="Enter field placeholder"
          />
          <div class="font-weight-bold mb-2 mt-3">Name <i class="ph-asterisk ph-xs text-danger" /></div>
          <TextField
            v-model="localField.name"
            hide-details
            placeholder="Enter field name"
          />
          <div v-if="field.type == 'File Uploader'">
            <div class="font-weight-bold mb-2 mt-3">Type <i class="ph-asterisk ph-xs text-danger" /></div>
            <div class="upload-type">
              <div class="upload-type-item">
                <input type="radio" id="single" v-model="localField.singleFile" :value="false" />
                <label for="single">Single File</label>
              </div>
              <div class="upload-type-item">
                <input type="radio" id="multiple" v-model="localField.singleFile" :value="true"/>
                <label for="multiple">Multiple File</label>
              </div>
            </div>
          </div>
          <div v-if="localField.type == 'Select'">
            <div class="font-weight-bold mb-2 mt-3">Options <i class="ph-asterisk ph-xs text-danger" /></div>
            <div v-for="(option, index) in localField.options" class="listItem" :key="index">
              <TextField
                v-model="localField.options[index]"
                hide-details
                placeholder="Enter option name"
              />
              <v-btn
                icon="mdi-trash-can-outline"
                size="small"
                class="btn-inline"
                @click="removeOption(index)"
              ></v-btn>
            </div>
            <div class="d-flex justify-center">
              <v-btn @click="addOption">
                <i class="ri-add-circle-line ri-icons" style="font-size: 20px;"></i>
              </v-btn>
            </div>
          </div>
          <div v-if="localField.type == 'List'">
            <div class="font-weight-bold mb-2 mt-3">Lists <i class="ph-asterisk ph-xs text-danger" /></div>
            <div v-for="(item, index) in localField.items" class="listItem" :key="index">
              <TextField
                v-model="localField.items[index]"
                hide-details
                placeholder="Enter list name"
              />
              <v-btn
                icon="mdi-trash-can-outline"
                size="small"
                class="btn-inline"
                @click="removeItem(index)"
              ></v-btn>
            </div>
            <div class="d-flex justify-center">
              <v-btn @click="addItem">
                <i class="ri-add-circle-line ri-icons" style="font-size: 20px;"></i>
              </v-btn>
            </div>
          </div>
          <div v-if="localField.type == 'Custom Radio'">
            <div class="font-weight-bold mb-2 mt-3">Radios <i class="ph-asterisk ph-xs text-danger" /></div>
            <div v-for="(option, index) in localField.radios" class="listItem" :key="index">
              <TextField
                v-model="localField.radios[index].title"
                hide-details
                placeholder="Enter option title"
                class="mb-1"
              />
              <TextField
                v-model="localField.radios[index].desc"
                hide-details
                placeholder="Enter option description"
                class="mb-1"
              />
              <v-btn
                icon="mdi-trash-can-outline"
                size="small"
                class="btn-inline"
                @click="removeRadio(index)"
              ></v-btn>
              <v-divider></v-divider>
            </div>
            <div class="d-flex justify-center">
              <v-btn @click="addRadio">
                <i class="ri-add-circle-line ri-icons" style="font-size: 20px;"></i>
              </v-btn>
            </div>
          </div>
          <div v-if="localField.type == 'Text'">
            <div class="font-weight-bold mb-2 mt-3">Content <i class="ph-asterisk ph-xs text-danger" /></div>
            <QuillEditor
                v-model:content="localField.text"
                contentType="html"
                :options="quillOptions"
            />
          </div>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="d-flex justify-end">
        <div>
          <v-btn color="danger" variant="text" @click="closeModal">
            <i class="ph-x me-1" /> Close
          </v-btn>
          <v-btn
            color="primary"
            variant="elevated"
            elevation="0"
            @click="saveChanges"
          >
            Save
          </v-btn>
        </div>
      </v-card-actions>
      </Card>
    </v-col>
  </v-row>
</template>
  
<script setup lang="ts">
import { defineProps, defineEmits, ref, watch } from "vue";
import { QuillEditor } from "@vueup/vue-quill";
import axios from "@/app/http/axios";
  
  interface FormField {
    id: number;
    type: string;
    label: string;
    description?: string;
    placeholder?: string;
    className?: string;
    name: string;
    required?: boolean;
    disabled?: boolean;
    options?: string[];
    items?: string[];
    radios?: object[];
    singleFile?: boolean;
    text?: string;
  }
  const toolbarOptions = [
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    ['bold', 'italic', 'underline', { 'script': 'sub'}, { 'script': 'super' }, 'divider', 'clean', 'link', 'image', 'video', 'code-block'],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'align': [] }],
    [{ 'indent': '-1'}, { 'indent': '+1' }, { 'color': ['white', 'black', 'green', 'red', 'yellow', 'blue'] }],
];
const quillOptions = {
  modules: {
    toolbar: {
      container: toolbarOptions,
      handlers: {
        image: function () {
          const input = document.createElement("input");
          input.setAttribute("type", "file");
          input.setAttribute("accept", "image/*");
          input.click();

          input.onchange = async () => {
            const file = input.files[0];
            if (file) {
              const formData = new FormData();
              formData.append("image", file);

              try {
                const response = await axios.post("/api/upload-image", formData, {
                  headers: { "Content-Type": "multipart/form-data" },
                });

                const range = this.quill.getSelection();
                this.quill.insertEmbed(range.index, "image", response.data.url);
              } catch (error) {
                console.error("Upload failed:", error);
              }
            }
          };
        },
      },
    },
  },
  placeholder: "Edit your content...",
  theme: "snow",
};
  const props = defineProps<{ field: FormField | null }>();
  const emit = defineEmits(["update", "close"]);
  
  const localField = ref<FormField>({} as FormField);
  
  watch(
    () => props.field,
    (newField) => {
      if (newField) localField.value = { ...newField };
    },
    { deep: true, immediate: true }
  );
  
  const saveChanges = () => {
    emit("update", localField.value);
    emit("close");
  };
  
  const closeModal = () => {
    emit("close");
  };
  const addOption = () => {
  if (!localField.value.options) localField.value.options = [];
  localField.value.options.push("");
};

const removeOption = (index: number) => {
  localField.value.options?.splice(index, 1);
};

const addItem = () => {
  if (!localField.value.items) localField.value.items = [];
  localField.value.items.push("");
};

const removeItem = (index: number) => {
  localField.value.items?.splice(index, 1);
};

const addRadio = () => {
  if (!localField.value.radios) localField.value.radios = [];
  localField.value.radios.push({title: '', desc: ''});
};

const removeRadio = (index: number) => {
  localField.value.radios?.splice(index, 1);
};

</script>

<style scoped>
.btn-inline {
  position: absolute;
  top: 1px;
  right: 1px;
  background: transparent;
  z-index: 9999;
}
.listItem {
  position: relative;
  margin-bottom: 5px;
}
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-content {
  background: white;
  padding: 20px;
  border-radius: 10px;
  width: 400px;
}
.modal-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 10px;
}
input {
  width: 100%;
  padding: 5px;
  margin: 5px 0;
}
.checkbox-group {
  display: flex;
  justify-content: space-between;
}
.upload-type {
  display: flex;
  justify-content: start;
  align-items: center;
}
.upload-type-item {
  display: flex;
  justify-content: start;
  align-items: center;
  margin-right: 1rem;
}
.upload-type-item input {
  width: 13px;
  margin-right: 10px;
}
</style>
  