<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import {useToast} from 'vue-toast-notification';
import Swal from 'sweetalert2';
import draggable from 'vuedraggable';

const toast = useToast();
const router = useRouter();
const route = useRoute();
const category_id = route.query.id;
const workspace_id = route.query.workspace_id;
const name = ref('');
const status = ref(true);
const issues = ref([]);
const isShow = ref(false);
const issue_name = ref('');
const issue_status = ref(true);
const departments = ref([]);
const selectDeps = ref([]);

const handleSave = async () => {
    const response = await axios.post(`/api/category/${category_id}`, {name: name.value, status: status.value, departments: selectDeps.value});
    issues.value.map(async (item, index) => {
        await axios.post(`/api/issue/${item.id}`, {order: index+1});
    });
    if(response) {
        router.push({path: '/edit_workspace', query: {id: workspace_id}});
        toast.success('Category updated successfully.', {position: 'top-right'});
    }
}

const handleCreate = async () => {
    const response = await axios.post('/api/issue', {name: issue_name.value, category_id: category_id, status: issue_status.value});
    if(response) {
        toast.success('Create issue successfully', {position: 'top-right'});
        issue_name.value = '';
        issues.value = issues.value.concat(response.data);
        isShow.value = false;
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
            const response = await axios.delete(`/api/issue/${id}`);
            if(response) {
                issues.value = issues.value.filter(item => item.id != id);
            }
            Swal.fire("Deleted!", "Issue deleted successfully.", "success");
        }
    });
    
}

onMounted(async () => {
    const response = await axios.get(`/api/category/${category_id}`);
    if(response) {
        name.value = response.data.name;
        status.value = !!response.data.status;
        issues.value = response.data.issues;
        selectDeps.value = response.data.departments;
    }
    const deps = await axios.get('/api/department');
    if(deps) {
        departments.value = deps.data;
    }
})
</script>

<template>
    <v-row justify="space-between" class="mt-5">
        <v-btn
            class="ml-5"
            color="primary"
            @click="router.push({path: '/edit_workspace', query: {id: workspace_id}})"
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
            <Card title="Edit Category" class="h-100">
                <v-card-text>
                    <div class="font-weight-bold mb-2 mt-3">Name <i class="ph-asterisk ph-xs text-danger" /></div>
                    <TextField
                        v-model="name"
                        hide-details
                        placeholder="Enter category name"
                    />
                    <v-row class="mt-1">
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
                    <div class="font-weight-bold mb-2 mt-3">Departments <i class="ph-asterisk ph-xs text-danger" /></div>
                    <v-autocomplete
                        variant="solo"
                        density="compact"
                        :items="departments"
                        item-title="name"
                        item-value="id"
                        multiple
                        closable-chips
                        chips
                        class="menu-select-filter mt-2"
                        v-model="selectDeps"
                    />
                </v-card-text>
            </Card>
        </v-col>
        
        <v-col cols="12" lg="6">
            <Card class="h-100">
                <v-card-text>
                    <div class="mt-4 d-flex justify-end">
                        <v-btn
                            @click="isShow = true"
                            color="primary"
                        >
                            Add Issue
                        </v-btn>
                    </div>
                    <div>
                        <v-list>
                            <draggable v-model="issues" tag="div" item-key="id" handle=".drag-handle" animation="300">
                                <template #item="{ element }">
                                <v-list-item class="border rounded-lg mb-2">
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
                                                @click="router.push({path: '/issue', query: {id: element.id, category_id: category_id, workspace_id: workspace_id}})">
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
                            <v-list-item v-if="issues.length === 0">
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
                <h5 class="text-h6 font-weight-bold">Add issue</h5>
            </v-card-title>
            <v-card-text>
                <div class="font-weight-bold mb-2 mt-3">Name <i class="ph-asterisk ph-xs text-danger" /></div>
                <TextField
                    v-model="issue_name"
                    hide-details
                    placeholder="Enter issue name"
                />
                <v-row class="mt-1">
                    <v-col cols="2">
                        <div class="font-weight-bold mb-2 mt-3">Status <i class="ph-asterisk ph-xs text-danger" /></div>
                    </v-col>
                    <v-col cols="10">
                        <v-switch
                            v-model="issue_status"
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