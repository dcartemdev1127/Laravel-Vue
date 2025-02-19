<template>
    <div v-if="show" class="modal">
      <div class="modal-content">
        <h3>Edit {{ field?.label }} Field</h3>
        <label>Label</label>
        <input v-model="localField.label" placeholder="Enter field label" />
  
        <label>Description</label>
        <input v-model="localField.description" placeholder="Enter field description" />
  
        <label>Placeholder</label>
        <input v-model="localField.placeholder" placeholder="Enter placeholder text" />
  
        <label>Class Name</label>
        <input v-model="localField.className" placeholder="Enter class name" />
  
        <label>Name</label>
        <input v-model="localField.name" placeholder="Field name" disabled />
  
        <div class="checkbox-group">
          <label>
            <input type="checkbox" v-model="localField.required" /> Required
          </label>
          <label>
            <input type="checkbox" v-model="localField.disabled" /> Disabled
          </label>
        </div>
        <!-- Dropdown Options -->
<div v-if="localField.type === 'select'">
  <label>Options</label>
  <div v-for="(option, index) in localField.options" :key="index">
    <input v-model="localField.options[index]" placeholder="Option" />
    <button @click="removeOption(index)">❌</button>
  </div>
  <button @click="addOption">➕ Add Option</button>
</div>

<!-- List Items -->
<div v-if="localField.type === 'list'">
  <label>List Items</label>
  <div v-for="(item, index) in localField.items" :key="index">
    <input v-model="localField.items[index]" />
    <button @click="removeItem(index)">❌</button>
  </div>
  <button @click="addItem">➕ Add Item</button>
</div>
        <div class="modal-actions">
          <button @click="saveChanges">Save changes</button>
          <button @click="closeModal">Cancel</button>
        </div>
      </div>
    </div>
    
  </template>
  
  <script setup lang="ts">
  import { defineProps, defineEmits, ref, watch } from "vue";
  
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
  }
  
  const props = defineProps<{ show: boolean; field: FormField | null }>();
  const emit = defineEmits(["update", "close"]);
  
  const localField = ref<FormField>({} as FormField);
  
  // Watch changes in props.field and update localField
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

  </script>
  
  <style scoped>
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
  </style>
  