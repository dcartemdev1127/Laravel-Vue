<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import {useToast} from 'vue-toast-notification';
import { useRouter, useRoute } from 'vue-router';
import { QuillEditor } from "@vueup/vue-quill";
import draggable from "vuedraggable";

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
    const response = await axios.post('/api/step', {name: name.value, form_id: form_id});
    if(response) {
        steps.value = steps.value.concat(response.data);
        isShow.value = false;
        name.value = '';
        toast.success('Step created successfully.', {position: 'top-right'});
    }
}

const handleDelete = async (id: string) => {
    const response = await axios.delete(`/api/step/${id}`);
    if(response) {
        steps.value = steps.value.filter(item => item.id != id);
        toast.success('Step deleted successfully.', {position: 'top-right'});
    }
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
  min-height: 100px;
}
.drag-handle {
  cursor: grab;
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
            <div class="font-weight-medium mb-2">
                Header <i class="ph-asterisk ph-xs text-danger" />
            </div>
            <div>
                <QuillEditor
                    v-model:content="header"
                    contentType="html"
                    theme="snow"
                />
            </div>
            <!-- <p>Output:</p>
            <div v-html="header"></div> -->
        </v-col>
        <v-col cols="12">
            <div class="font-weight-medium mb-2 mt-5">
                Footer <i class="ph-asterisk ph-xs text-danger" />
            </div>
            <div>
                <QuillEditor
                    v-model:content="footer"
                    contentType="html"
                    theme="snow"
                />
            </div>
        </v-col>
        <v-col col="12" v-if="form_id">
            <v-btn
                @click="isShow = true"
                color="blue"
            >
                Add step
            </v-btn>
        </v-col>
        <v-col cols="12">
            <v-card
                max-width="300"
            >
                <v-list>
                    <draggable v-model="steps" tag="div" item-key="id" handle=".drag-handle" animation="300">
                        <template #item="{ element }">
                        <v-list-item>
                            <template v-slot:prepend>
                            <v-icon class="drag-handle" style="cursor: grab;">mdi-drag</v-icon>
                            </template>
                            <v-list-item-content>
                            <v-list-item-title>{{ element.name }}</v-list-item-title>
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
            </v-card>
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
            <v-card-text class="mt-5">
                <v-row justify="center" class="align-center">
                    <v-col cols="12">
                        <div class="font-weight-medium mb-2 mt-5">
                            Step Name <i class="ph-asterisk ph-xs text-danger" />
                        </div>
                        <v-text-field
                            v-model="name"
                            isRequired
                            placeholder="Enter step name"
                            hide-details
                        />
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