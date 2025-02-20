<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import {useToast} from 'vue-toast-notification';
import draggable from "vuedraggable";
import EditFieldModal from "./EditFieldModal.vue";
import ImageUploader from "@/app/common/components/ImageUploader.vue";
import { GoogleMap } from 'vue3-google-map'
import appConfigs from "@/app/appConfigurations";

const apiKey = appConfigs.googleMapAPIKey
const center = { lat: 42.3455, lng: -71.0983 }
const toast = useToast();
const router = useRouter();
const route = useRoute();
const step_id = route.query.id;
const form_id = route.query.form_id;
const name = ref('');
const status = ref(true);
const defaultModel = ref('');

const handleSave = async () => {
  const response = await axios.post(`/api/step/${step_id}`, {name: name.value, status: status.value, fields: formFields.value});
  if(response) {
      router.push({path: '/form', query: {id: form_id}});
      toast.success('step updated successfully.', {position: 'top-right'});
  }
}

onMounted(async () => {
  const response = await axios.get(`/api/step/${step_id}`);
  if(response) {
      name.value = response.data.name;
      status.value = !!response.data.status;
      formFields.value = response.data.fields || [];
  }
});

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
  value?: number;
  items?: string[];
  options?: string[];
  radios?: string[];
}

const components = ref<FormField[]>([
  { id: 1, type: "text", label: "Text Input", name: "text_1" },
  { id: 2, type: "email", label: "Email Input", name: "email_1" },
  { id: 3, type: "textarea", label: "Textarea", name: "textarea_1" },
  { id: 4, type: "select", label: "Dropdown", name: "select_1", options: ["Option 1", "Option 2"] },
  { id: 5, type: "list", label: "List Input", name: "list_1", items: ['List 1', 'List 2'] },
  { id: 6, type: "file", label: "File Upload", name: "file_1" },
  { id: 7, type: "datetime", label: "Date Time Picker", name: "date_1" },
  { id: 8, type: "google-map", label: "Google Map", name: "map_1" },
  { id: 9, type: "checkbox", label: "Checkbox", name: "checkbox_1" },
  { id: 10, type: "rating", label: "Rating", name: "rating_1", value: 0 },
  { id: 11, type: "radio", label: "Radio", name: "radio_1", radios: ['Radio 1', 'Radio 2'] },
]);

const formFields = ref<FormField[]>([]);
const showModal = ref(false);
const editingField = ref<FormField | null>(null);

// Add new field
const addField = (component: FormField) => {
  formFields.value.push({ ...component, id: Date.now() });
};

// Remove field
const removeField = (id: number) => {
  formFields.value = formFields.value.filter(field => field.id !== id);
};

// Edit field
const editField = (field: FormField) => {
  editingField.value = { ...field };
  showModal.value = true;
};

// Save edited field
const saveEdit = (updatedField: FormField) => {
  const index = formFields.value.findIndex(f => f.id === updatedField.id);
  if (index !== -1) formFields.value[index] = updatedField;
  closeEdit();
};

// Close edit modal
const closeEdit = () => {
  showModal.value = false;
  editingField.value = null;
};
const newListItem = ref("");

const addListItem = (field: FormField) => {
  if (newListItem.value.trim()) {
    field.items?.push(newListItem.value);
    newListItem.value = "";
  }
};
</script>

<style scoped>
.container {
  display: flex;
  justify-content: space-between;
  padding: 20px;
}
.left-panel, .center-panel, .right-panel {
  width: 30%;
  padding: 10px;
  border: 1px solid #ccc;
}
.draggable-list {
  min-height: 200px;
  padding: 10px;
  border: 2px dashed #ddd;
}
.form-item {
  display: flex;
  justify-content: space-between;
  padding: 5px;
  background: #f9f9f9;
  margin: 5px 0;
  cursor: grab;
}
.preview-item {
  padding: 5px;
  margin: 5px 0;
}
</style>

<template>
    <v-row justify="space-between" class="mt-5">
      <v-btn
        class="ml-5"
        color="primary"
        @click="router.push({path: '/form', query: {id: form_id}})"
      >
        BACK
      </v-btn>
      <v-btn
        class="mr-5"
        color="primary"
        @click="handleSave()"
      >
        SAVE
      </v-btn>
    </v-row>
    <v-row>
      <v-col cols="12" lg="6">
        <Card title="Edit Step" class="h-100">
          <v-card-text>
            <div class="font-weight-bold mb-2 mt-3">Name <i class="ph-asterisk ph-xs text-danger" /></div>
            <TextField
              v-model="name"
              hide-details
              placeholder="Enter step name"
            />
            <v-row class="mt-1">
              <v-col cols="2">
                <div class="font-weight-bold mb-2 mt-3">Status <i class="ph-asterisk ph-xs text-danger" /></div>
              </v-col>
              <v-col cols="10">
                <v-switch
                  v-model="status"
                  density="compact"
                  color="primary"
                  :label="status ? 'Enable' : 'Disable'"
                  hide-details
                >
                </v-switch>
              </v-col>
            </v-row>
          </v-card-text>
        </Card>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <Card title="Edit field" class="h-100">
          <v-card-text>
            <v-row>
              <v-col cols="12" lg="3" md="5">
                <Card title="Components" class="h-100">
                  <v-card-text>
                    <div class="flex flex-col items-start gap-3">
                      <div 
                        v-for="component in components"
                        :key="component.type"
                        class="flex items-center gap-1"
                        >
                        <v-btn
                          class="inline-flex items-center justify-center border rounded-x5 my-1"
                          style="border-radius: .75rem"
                          @click="addField(component)"
                        >
                          {{ component.label }}
                        </v-btn>
                      </div>
                    </div>
                  </v-card-text>
                </Card>
              </v-col>
              <v-col cols="12" lg="4" md="7">
                <Card title="Fields" class="h-100">
                  <v-card-text>
                    <draggable 
                      v-model="formFields" 
                      item-key="id"
                      animation="300"
                    >
                      <template #item="{ element }">
                        <div 
                          class="d-flex justify-space-between items-center gap-1 border py-1 pl-4 w-full my-1"
                          style="align-items: center; border-radius: .75rem"
                        >
                          <span class="inline-flex items-center justify-center text-sm">{{ element.label }}</span>
                          <div>
                            <v-btn
                              icon="mdi-pencil-outline" 
                              size="small"
                              class="inline-flex items-center justify-center"
                              @click="editField(element)"
                            ></v-btn>
                            <v-btn
                              icon="mdi-trash-can-outline" 
                              size="small"
                              class="inline-flex items-center justify-center"
                              @click="removeField(element.id)"
                            ></v-btn>
                          </div>
                        </div>
                      </template>
                    </draggable>
                  </v-card-text>
                </Card>
              </v-col>
              <v-col cols="12" lg="5">
                <Card title="Preview" class="h-100">
                  <v-card-text>
                    <div v-for="field in formFields" :key="field.id">
                      <div class="font-weight-bold mb-1">{{field.label}}</div>
                      <v-text-field
                        v-if="field.type == 'text'"
                        hide-details
                        variant="solo"
                        density="compact"
                        class="text-field-component"
                        :placeholder="field.placeholder || 'text input'"
                      />
                      <v-text-field
                        v-if="field.type == 'email'"
                        hide-details
                        variant="solo"
                        :placeholder="field.placeholder || 'example@gmail.com'"
                        density="compact"
                        class="text-field-component"
                      >
                        <template #prepend-inner>
                          <i class="ph-envelope pe-3" />
                        </template>
                      </v-text-field>
                      <v-textarea
                        v-if="field.type == 'textarea'"
                        hide-details
                        variant="solo"
                        density="compact"
                        :placeholder="field.placeholder || 'textarea'"
                        class="text-field-component"
                      />
                      <VueDatePicker
                        v-if="field.type == 'datetime'"
                        v-model="defaultModel"
                        :teleport="true"
                        placeholder="Select date"
                      />
                      <ImageUploader v-if="field.type == 'file'"/>
                      <v-rating
                        v-if="field.type == 'rating'"
                        density="comfortable"
                        clearable
                        :model-value="3"
                        active-color="warning"
                      />
                      <v-select
                        v-if="field.type == 'select'"
                        :label="field.placeholder"
                        variant="outlined"
                        :items="field.options"
                        density="compact"
                        class="mt-2"
                      />
                      <v-checkbox
                        v-if="field.type == 'checkbox'"
                        :model-value="true"
                        color="primary"
                        hide-details
                        density="compact"
                      >
                        <template #label><span>{{ field.placeholder || 'checkbox' }}</span></template>
                      </v-checkbox>
                      <v-radio-group v-if="field.type == 'radio'" :model-value="field.radios[0]" color="primary">
                        <v-radio 
                          v-for="radio in field.radios" 
                          density="compact" 
                          :value="radio"
                        >
                          <template #label><span>{{ radio }}</span></template>
                        </v-radio>
                      </v-radio-group>
                      <v-list v-if="field.type == 'list'" :items="field.items"></v-list>
                      <GoogleMap
                        v-if="field.type == 'google-map'"
                        :api-key="apiKey"
                        style="width: 100%; height: 300px"
                        :center="center"
                        :zoom="13"
                      >
                      </GoogleMap>
                      <p class="text-muted my-1 ml-1">{{field.description}}</p>
                    </div>
                  </v-card-text>
                </Card>
              </v-col>
            </v-row>
          </v-card-text>
        </Card>
      </v-col>
    </v-row>
    <!-- Edit Modal -->
     <v-dialog v-model="showModal" class="w-50">
       <EditFieldModal :field="editingField" @update="saveEdit" @close="closeEdit" />
    </v-dialog>
</template>