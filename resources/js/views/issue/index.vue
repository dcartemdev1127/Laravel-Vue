<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import {useToast} from 'vue-toast-notification';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const category_id = route.query.id;
const toast = useToast();
const headers = ref([
    {key: 'name', title: 'Name', sortable: true},
    {key: 'status', title: 'Status', sortable: true},
    {key: 'action', title: 'Action'}
]);

const loadingTable = ref(false);
const isShow = ref(false);
const issues = ref([]);
const name = ref('');

const handleCreate = async () => {
    const response = await axios.post('/api/issue', {name: name.value, category_id: category_id});
    if(response) {
        toast.success('Create issue successfully', {position: 'top-right'});
        issues.value = issues.value.concat(response.data);
        isShow.value = false;
    }
}

const handleDelete = async (id: string) => {
    const response = await axios.delete(`/api/issue/${id}`);
    if(response) {
        issues.value = issues.value.filter(item => item.id != id);
        toast.success('Issue deleted successfully.', {position: 'top-right'});
        isShow.value = false;
    }
}

onMounted(async () => {
    loadingTable.value = true;
    const response = await axios.get(`/api/issue/category/${category_id}`);
    if(response) {
        issues.value = response.data;
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
                    :items="issues"
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
                            <td>{{ item.status }}</td>
                            <td>
                                <div>
                                    <v-btn icon="mdi-pencil-outline" size="small" @click="router.push({path: '/edit_category', query: {id: item.id}})"></v-btn>
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
                <h5 class="text-h6 font-weight-bold">Update User Information</h5>
            </v-card-title>
            <v-card-text class="mt-5">
                <v-row justify="center" class="align-center">
                    <v-col cols="12">
                        <div class="font-weight-medium mb-2 mt-5">
                            category Name <i class="ph-asterisk ph-xs text-danger" />
                        </div>
                        <v-text-field
                            v-model="name"
                            isRequired
                            placeholder="Enter category name"
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