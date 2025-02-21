<script lang="ts" setup>
import { ref, onMounted, nextTick, watchEffect } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import {useToast} from 'vue-toast-notification';
import EditFieldModal from "./EditFieldModal.vue";
import draggable from "vuedraggable";
import ImageUploader from "@/app/common/components/ImageUploader.vue";
import IssueList from './IssueList.vue';
import Map from './Map.vue';

import { GoogleMap,Marker, MarkerCluster } from "vue3-google-map";
const apiKey = "AIzaSyANKjpCtaUho8oy53T63IFl75Ia9qrdDlI";
// const mapCenter = ref({ lat: 34.1706, lng: -118.8376 }); // Default: Thousand Oaks
// const zoomLevel = ref(12);
// const areaName = ref("");
// const center = { lat: 40.689247, lng: -74.044502 }
// const markerOptions = { position: center, label: 'L', title: 'LADY LIBERTY' }
// interface LatLng {
//   lat: number;
//   lng: number;
// }

// const mapRef = ref<InstanceType<typeof GoogleMap> | null>(null);
// const mapInstance = ref<google.maps.Map | null>(null);
// const mapCenter = ref<LatLng>({ lat: 34.1706, lng: -118.8376 }); 

// const onMapLoad = (map: google.maps.Map) => {
//   mapInstance.value = map;
//   console.log("Map Loaded:", mapInstance.value);
// };

// const updateMapCenter = () => {
//   if (!mapInstance.value) {
//     console.warn("Map is not ready yet!");
//     return;
//   }

//   const center = mapInstance.value.getCenter();
//   if (center) {
//     mapCenter.value = { lat: center.lat(), lng: center.lng() };
//     console.log("Updated Center:", mapCenter.value);
//   }
// };

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
  // await nextTick();
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
  { id: 11, type: "issues", label: "Issue List", name: "issues" },
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
.dialog-card {
  overflow: hidden;
  max-height: 90vh; 
}
.custom-scroll {
  max-height: 80vh; 
  overflow: auto; 
}
.info-box {
  margin-top: 10px;
  padding: 10px;
  background: white;
  border-radius: 5px;
  box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
  max-width: 300px;
}

.info-box p {
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
              <v-col cols="12" lg="2" md="5">
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
              <v-col cols="12" lg="6">
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
                      <v-radio-group 
                        v-if="field.type == 'radio'" 
                        :model-value="field.radios[0]" 
                        color="primary"
                      >
                        <v-radio 
                          v-for="radio in field.radios" 
                          density="compact" 
                          :value="radio"
                        >
                          <template #label><span>{{ radio }}</span></template>
                        </v-radio>
                      </v-radio-group>
                      <v-list v-if="field.type == 'list'" :items="field.items"></v-list>
                      <IssueList v-if="field.type == 'issues'"/>
                      <!-- <GoogleMap
                          :api-key="apiKey"
                          style="width: 100%; height: 500px"
                          :center="center"
                          :zoom="15"
                        >
                          <Marker :options="markerOptions" />
                        </GoogleMap> -->
                        <!-- <GoogleMap
                        v-if="field.type == 'google-map'"
                          ref="mapRef"
                          :api-key="apiKey"
                          :center="mapCenter"
                          :zoom="zoomLevel"
                          style="width: 100%; height: 500px"
                          @idle="updateMapCenter"
                           @on-load="onMapLoad"
                        />
                        <div class="info-box">
                          <p><strong>Latitude:</strong> {{ mapCenter.lat }}</p>
                          <p><strong>Longitude:</strong> {{ mapCenter.lng }}</p>
                          <p><strong>Area:</strong> {{ areaName || "Loading..." }}</p>
                        </div> -->
                        <!-- <Map v-if="field.type == 'google-map'" /> -->
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
     <v-dialog v-model="showModal" class="w-50" max-height="80vh" @click:outside="closeEdit">
      <v-card class="dialog-card">
        <div data-simplebar class="custom-scroll px-3">
          <EditFieldModal 
           :field="editingField" 
           @update="saveEdit" 
           @close="closeEdit" />
        </div>
      </v-card>
    </v-dialog>
</template>