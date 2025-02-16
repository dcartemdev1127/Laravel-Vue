<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";

const headers = ref([
    {key: 'name', title: 'Name', sortable: true},
    {key: 'city', title: 'City', sortable: true},
    {key: 'department', title: 'Department', sortable: true},
    {key: 'role', title: 'Role', sortable: true},
    {key: 'action', title: 'Action'}
]);

const users = ref([]);

const handleUpdate = async (id: string, role: string) => {
    const response = await axios.post(`/api/users/${id}/update`, {role: role});
    if(response) {

    }
};

const handleDelete = async (id: string) => {
    const response = await axios.delete(`/api/users/${id}`);
    if(response) {
        users.value.filter(item => item.id != id);
    }
};

onMounted(async () => {
    const response = await axios.get('/api/users');
    if(response) {
        users.value = response.data;
    }
})
</script>

<template>
    <v-row justify="space-around" class="mt-5">
        <v-col cols="10">
            <v-data-table
                :headers="headers"
                :items="users"
                style="border-radius: 12px;"
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
                        <td>{{ item.city }}</td>
                        <td>{{ item.department }}</td>
                        <td>{{ item.role }}</td>
                        <td>
                            <div>
                                <v-btn icon="mdi-pencil-outline" size="small" @click="handleUpdate(item.id, item.role)"></v-btn>
                                <v-btn icon="mdi-trash-can-outline" size="small" @click="handleDelete(item.id)"></v-btn>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-col>
        
    </v-row>
</template>