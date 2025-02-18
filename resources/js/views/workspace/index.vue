<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import {useToast} from 'vue-toast-notification';
import { useRouter } from 'vue-router';

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

const handleCreate = async () => {
    const response = await axios.post('/api/workspace', {name: name.value});
    if(response) {
        toast.success('Create workspace successfully', {position: 'top-right'});
        workspaces.value = workspaces.value.concat(response.data);
        isShow.value = false;
    }
}

const handleDelete = async (id: string) => {
    const response = await axios.delete(`/api/workspace/${id}`);
    if(response) {
        workspaces.value = workspaces.value.filter(item => item.id != id);
        toast.success('Workspace deleted successfully.', {position: 'top-right'});
        isShow.value = false;
    }
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
                color="blue"
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
                            <td>{{ item.status ? 'Enable' : 'Disable' }}</td>
                            <td>{{ item.form }}</td>
                            <td>
                                <div>
                                    <v-btn icon="mdi-pencil-outline" size="small" @click="router.push({path: '/edit_workspace', query: {id: item.id}})"></v-btn>
                                    <v-btn icon="mdi-trash-can-outline" size="small" @click="handleDelete(item.id)"></v-btn>
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
            <v-card-text class="mt-5">
                <v-row justify="center" class="align-center">
                    <v-col cols="12">
                        <div class="font-weight-medium mb-2 mt-5">
                            Workspace Name <i class="ph-asterisk ph-xs text-danger" />
                        </div>
                        <v-text-field
                            v-model="name"
                            isRequired
                            placeholder="Enter workspace name"
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