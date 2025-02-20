<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import {useToast} from 'vue-toast-notification';
import { useRouter, useRoute } from 'vue-router';
import { QuillEditor } from "@vueup/vue-quill";
import draggable from "vuedraggable";
import Swal from 'sweetalert2';

const toast = useToast();
const route = useRoute();
const router = useRouter();
const form_id = route.query.id;
const workspace_id = route.query.workspace_id;
const header = ref('');
const footer = ref('');
const steps = ref([]);
const isShow = ref(false);
const name = ref('');
const status = ref(true);
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

const handleSave = async () => {
    if(!form_id) {
        const response = await axios.post('/api/form', {workspace_id: workspace_id, header: header.value, footer: footer.value});
        if(response) {
            router.push({path: '/workspace'});
            toast.success('Form created successfully.', {position: 'top-right'});
        }
    } else {
        const response = await axios.post(`/api/form/${form_id}`, {header: header.value, footer: footer.value});
        steps.value.map(async (item, index) => {
            await axios.post(`/api/step/${item.id}`, {order: index+1});
        });
        if(response) {
            router.push({path: '/workspace'});   
            toast.success('Form updated successfully.', {position: 'top-right'});
        }
    }
}

const handleCreate = async () => {
    const response = await axios.post('/api/step', {name: name.value, form_id: form_id, status: status.value});
    if(response) {
        steps.value = steps.value.concat(response.data);
        isShow.value = false;
        name.value = '';
        toast.success('Step created successfully.', {position: 'top-right'});
    }
}

const handleDelete = async (id: string) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then(async (result) => {
        if (result.isConfirmed) {
            const response = await axios.delete(`/api/step/${id}`);
            if(response) {
                steps.value = steps.value.filter(item => item.id != id);
            }
            Swal.fire("Deleted!", "Step deleted successfully.", "success");
        }
    });
}

onMounted(async () => {
    if(form_id) {
        const response = await axios.get(`/api/form/${form_id}`);
        if(response) {
            header.value = response.data.header;
            footer.value = response.data.footer;
            steps.value = response.data.steps;
        }
    }
})
</script>

<style>
.ql-editor {
  min-height: 150px;
}
.drag-handle {
  cursor: grab;
}
</style>

<template>
    <v-row justify="space-between" class="mt-5">
        <v-btn
            class="ml-5"
            color="primary"
            @click="router.push({path: '/workspace'})"
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
            <Card title="Edit form" class="h-100">
                <v-card-text>
                    <div class="font-weight-bold mb-2 mt-3">
                        Header <i class="ph-asterisk ph-xs text-danger" />
                    </div>
                    <div>
                        <QuillEditor
                            v-model:content="header"
                            contentType="html"
                            :options="quillOptions"
                        />
                    </div>
                    <div class="font-weight-bold mb-2 mt-3">
                        Footer <i class="ph-asterisk ph-xs text-danger" />
                    </div>
                    <div>
                        <QuillEditor
                            v-model:content="footer"
                            contentType="html"
                            :options="quillOptions"
                        />
                    </div>
                    <!-- <p>Output:</p>
                    <div v-html="header"></div> -->
                </v-card-text>
            </Card>
        </v-col>
        
        <v-col col="12" lg="6" v-if="form_id">
            <Card class="h-100">
                <v-card-text>
                    <div class="mt-4 d-flex justify-end">
                        <v-btn
                            @click="isShow = true"
                            color="primary"
                        >
                            Add step
                        </v-btn>
                    </div>
                    <div>
                        <v-list>
                            <draggable v-model="steps" tag="div" item-key="id" handle=".drag-handle" animation="300">
                                <template #item="{ element }">
                                <v-list-item class="border rounded-lg mb-2" >
                                    <template v-slot:prepend>
                                    <v-icon class="drag-handle" style="cursor: grab;">mdi-drag</v-icon>
                                    </template>
                                    <v-list-item-content>
                                    <v-list-item-title>
                                        <div class="d-flex justify-space-between">
                                            <div>{{ element.name }}</div>
                                            <div class="mr-5">
                                                <v-chip
                                                    size="x-small"
                                                    :color="element.status ? 'success' : 'danger'"
                                                    variant="outlined"
                                                >
                                                    {{ element.status ? 'Enable' : 'Disable' }}
                                                </v-chip>
                                            </div>
                                        </div>
                                    </v-list-item-title>
                                    </v-list-item-content>
                                    <template v-slot:append>
                                        <div>
                                            <v-btn 
                                                icon="mdi-pencil-outline" 
                                                size="small" 
                                                @click="router.push({path: '/step', query: {id: element.id, form_id: form_id}})">
                                            </v-btn>
                                            <v-btn 
                                                icon="mdi-trash-can-outline" 
                                                size="small" 
                                                @click="handleDelete(element.id)">
                                            </v-btn>
                                        </div>
                                    </template>
                                </v-list-item>
                                </template>
                            </draggable>
                
                            <!-- No Data Message inside List -->
                            <v-list-item v-if="steps.length === 0">
                                <v-list-item-content class="text-center">
                                <v-list-item-title class="text-grey">No data available</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </div>
                </v-card-text>
            </Card>
        </v-col>
    </v-row>
    <v-dialog
        class="w-50"
        v-model="isShow"
    >
        <v-card>
            <v-card-title class="text-center">
                <h5 class="text-h6 font-weight-bold">Add step</h5>
            </v-card-title>
            <v-card-text>
                <div class="font-weight-bold mb-2 mt-3">Name <i class="ph-asterisk ph-xs text-danger" /></div>
                <TextField
                    v-model="name"
                    hide-details
                    placeholder="Enter issue name"
                />
                <v-row>
                    <v-col cols="2">
                        <div class="font-weight-bold mb-2 mt-3">Status <i class="ph-asterisk ph-xs text-danger" /></div>
                    </v-col>
                    <v-col cols="10">
                        <v-switch
                            v-model="status"
                            color="primary"
                            density="compact"
                            :label="status ? 'Enable' : 'Disable'"
                            hide-details
                            >
                        </v-switch>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-divider>
            </v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    text="Close"
                    variant="plain"
                    @click="isShow = false"
                ></v-btn>

                <v-btn
                    color="primary"
                    text="Save"
                    variant="tonal"
                    @click="handleCreate()"
                ></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>