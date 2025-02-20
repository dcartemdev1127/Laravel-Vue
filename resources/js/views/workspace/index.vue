<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import {useToast} from 'vue-toast-notification';
import { useRouter } from 'vue-router';
import Swal from "sweetalert2";

const router = useRouter();
const toast = useToast();
const headers = ref([
    {key: 'name', title: 'Name', sortable: true},
    {key: 'status', title: 'Status', sortable: true},
    {key: 'form', title: 'Form'},
    {key: 'action', title: 'Action'}
]);

const loadingTable = ref(false);
const isShow = ref(false);
const workspaces = ref([]);
const name = ref('');
const status = ref(true);

const handleCreate = async () => {
    const response = await axios.post('/api/workspace', {name: name.value, status: status.value});
    if(response) {
        toast.success('Create workspace successfully', {position: 'top-right'});
        workspaces.value = workspaces.value.concat(response.data);
        isShow.value = false;
    }
}

const handleDelete = (id: string) => {
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
            const response = await axios.delete(`/api/workspace/${id}`);
            if(response) {
                workspaces.value = workspaces.value.filter(item => item.id != id);
            }
            Swal.fire("Deleted!", "Workspace deleted successfully.", "success");
        }
    });
}

onMounted(async () => {
    loadingTable.value = true;
    const response = await axios.get('/api/workspace');
    if(response) {
        workspaces.value = response.data;
        loadingTable.value = false;
    }
})
</script>

<template>
    <v-container>
        <v-row justify="end" class="mt-5">
            <v-btn
                class="mr-5"
                color="primary"
                @click="isShow = true"
            >
                ADD
            </v-btn>
        </v-row>
        <v-row justify="space-around" class="mt-5">
            <v-col cols="12">
                <v-data-table
                    :headers="headers"
                    :items="workspaces"
                    style="border-radius: 12px;"
                    class="customTable"
                    :loading="loadingTable"
                >
                    <template v-slot:headers="{ columns, isSorted, getSortIcon, toggleSort }">
                        <tr>
                            <template v-for="column in columns" :key="column.key">
                                <th class="bg-[#ACC3C2]">
                                    <span class="mr-2 cursor-pointer" @click="()=>toggleSort(column)">{{ column.title }}</span>
                                    <template v-if="isSorted(column)"><v-icon :icon="getSortIcon(column)"></v-icon></template>
                                </th>
                            </template>
                        </tr>
                    </template>
                    <template v-slot:item="{ item }">
                        <tr>
                            <td>{{ item.name }}</td>
                            <td>
                                <v-chip
                                    size="x-small"
                                    :color="item.status ? 'success' : 'danger'"
                                    variant="outlined"
                                >
                                    {{ item.status ? 'Enable' : 'Disable' }}
                                </v-chip>
                            </td>
                            <td>
                                <v-btn 
                                    icon="mdi-pencil-outline" 
                                    size="small" 
                                    @click="router.push({path: '/form', query: {id: item.form_id, workspace_id: item.id}})">
                                </v-btn>
                            </td>
                            <td>
                                <div>
                                    <v-btn 
                                        icon="mdi-pencil-outline" 
                                        size="small" 
                                        @click="router.push({path: '/edit_workspace', query: {id: item.id}})">
                                    </v-btn>
                                    <v-btn 
                                        density="comfortable"
                                        icon="mdi-trash-can-outline" 
                                        size="small" 
                                        @click="handleDelete(item.id)">
                                    </v-btn>
                                </div>
                            </td>
                        </tr>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
    </v-container>
    <v-dialog
        class="w-50"
        v-model="isShow"
    >
        <v-card>
            <v-card-title class="text-center">
                <h5 class="text-h6 font-weight-bold">Add workspace</h5>
            </v-card-title>
            <v-card-text>
                <div class="font-weight-bold mb-2 mt-3">Name <i class="ph-asterisk ph-xs text-danger" /></div>
                <TextField
                    v-model="name"
                    hide-details
                    placeholder="Enter workspace name"
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

<style>
.customTable th:last-child, td:last-child{
    text-align: center !important;
    width: 150px;
}
</style>