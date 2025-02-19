<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import {useToast} from 'vue-toast-notification';
import draggable from "vuedraggable";
import EditFieldModal from "./EditFieldModal.vue";

const toast = useToast();
const router = useRouter();
const route = useRoute();
const step_id = route.query.id;
const form_id = route.query.form_id;
const name = ref('');
const status = ref(true);

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
}

const components = ref<FormField[]>([
  { id: 1, type: "text", label: "Text Input", name: "text_1" },
  { id: 2, type: "email", label: "Email Input", name: "email_1" },
  { id: 3, type: "number", label: "Number Input", name: "number_1" },
  { id: 4, type: "select", label: "Dropdown", name: "select_1", options: ["Option 1", "Option 2"] },
  { id: 5, type: "list", label: "List Input", name: "list_1", items: [] },
  { id: 6, type: "file", label: "File Upload", name: "file_1" },
  { id: 7, type: "date", label: "Date Picker", name: "date_1" },
  { id: 8, type: "google-map", label: "Google Map", name: "map_1" },
  { id: 9, type: "checkbox", label: "Checkbox", name: "checkbox_1" },
  { id: 10, type: "rating", label: "Rating", name: "rating_1", value: 0 },
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
    <v-row justify="end" class="mt-5">
        <v-btn
            class="mr-5"
            color="blue"
            @click="handleSave()"
        >
            SAVE
        </v-btn>
    </v-row>
    <v-row>
        <v-col cols="12">
            <div class="font-weight-medium mt-5">
                Name <i class="ph-asterisk ph-xs text-danger" />
            </div>
        </v-col>
        <v-col cols="12">
            <v-text-field
                v-model="name"
                isRequired
                placeholder="Enter step name"
                hideDetails
            ></v-text-field>
        </v-col>
        <v-col cols="12">
            <div class="font-weight-medium mt-5">
                Status <i class="ph-asterisk ph-xs text-danger" />
            </div>
        </v-col>
        <v-col cols="12">
            <v-switch
                v-model="status"
                color="primary"
                :label="status ? 'Enable' : 'Disable'"
                hide-details
                >
            </v-switch>
        </v-col>
    </v-row>
    <div class="container">
    <!-- Left: Component Selection -->
    <div class="left-panel">
      <h3>Components</h3>
      <button v-for="component in components" :key="component.type" @click="addField(component)">
        {{ component.label }}
      </button>
    </div>

    <!-- Center: Drag & Drop Form Builder -->
    <div class="center-panel">
      <h3>Form Builder</h3>
      <draggable v-model="formFields" item-key="id" class="draggable-list">
        <template #item="{ element }">
          <div class="form-item">
            <span>{{ element.label }}</span>
            <button @click="editField(element)">✏️ Edit</button>
            <button @click="removeField(element.id)">🗑 Remove</button>
          </div>
        </template>
      </draggable>
    </div>

    <!-- Right: Preview -->
<div class="right-panel">
  <h3>Preview</h3>
  <div v-for="field in formFields" :key="field.id" class="preview-item">
    <label>{{ field.label }}</label>
    
    <!-- Text Inputs -->
    <input v-if="['text', 'email', 'number'].includes(field.type)" :type="field.type" :placeholder="field.label" />

    <!-- Dropdown -->
    <select v-if="field.type === 'select'">
      <option v-for="option in field.options" :key="option">{{ option }}</option>
    </select>

    <!-- List Input -->
    <div v-if="field.type === 'list'">
      <ul>
        <li v-for="(item, index) in field.items" :key="index">{{ item }}</li>
      </ul>
      <input v-model="newListItem" placeholder="Add item" />
      <button @click="addListItem(field)">➕ Add</button>
    </div>

    <!-- File Upload -->
    <input v-if="field.type === 'file'" type="file" />

    <!-- Date Picker -->
    <input v-if="field.type === 'date'" type="date" />

    <!-- Google Map -->
    <div v-if="field.type === 'google-map'" style="width: 100%; height: 200px; background: lightgray;">
      📍 Google Map Placeholder
    </div>

    <!-- Checkbox -->
    <input v-if="field.type === 'checkbox'" type="checkbox" />

    <!-- Rating -->
    <div v-if="field.type === 'rating'">
      <span v-for="star in 5" :key="star" @click="field.value = star" style="cursor: pointer;">
        {{ star <= field.value ? "⭐" : "☆" }}
      </span>
    </div>
  </div>
</div>


    <!-- Edit Modal -->
    <EditFieldModal :show="showModal" :field="editingField" @update="saveEdit" @close="closeEdit" />
  </div>
</template>