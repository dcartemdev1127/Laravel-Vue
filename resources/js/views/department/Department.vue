<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";

const formData = ref('');
const insertflag = ref(true);
const headers = ref([
    {key: 'name', title: 'Name', sortable: false},
    {ley: 'action', title: 'Action'}
]);

const departmemts = ref([]);

const handleDelete = async (id: string) => {
    const response = await axios.delete(`/api/department/${id}`);
    if(response) {
        departmemts.value = departmemts.value.filter(item => item.id != id);
    }
};

const handleUpdate = async (id: string) => {
    insertflag.value = false;
    const response = await axios.get(`/api/department/${id}`);
    if(response) {
        formData.value = response.data.name;
    }
};

const handleCreate = async () => {
    const response = await axios.post('/api/department', {name: formData.value});
    if(response) {
        departmemts.value = departmemts.value.concat(response.data);
        formData.value = '';
        insertflag.value = true;
    }
}

onMounted( async () => {
    const response = await axios.get('/api/department');
    if(response) {
        departmemts.value = response.data;
    }
});

</script>

<template>
    <v-row justify="space-around" class="mt-5">
        <v-col cols="9" lg="5">
            <v-data-table
                :headers="headers"
                :items="departmemts"
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
                        <td>
                            <div>
                                <v-btn icon="mdi-pencil-outline" size="small" @click="handleUpdate(item.id)"></v-btn>
                                <v-btn icon="mdi-trash-can-outline" size="small" @click="handleDelete(item.id)"></v-btn>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-col>
        <v-col cols="9" lg="5">
            <v-row>
                <v-col cols="12">
                    <div class="font-weight-bold mb-2 mt-3">Department Name</div>
                </v-col>
                <v-col cols="12">
                    <TextField
                        v-model="formData"
                        hide-details
                        placeholder="Enter department name"
                    />
                </v-col>
            </v-row>
            <v-row justify="end" class="pr-3">
                <v-btn color="blue" @click="handleCreate()">ADD</v-btn>
                <v-btn color="blue">UPDATE</v-btn>
            </v-row>
        </v-col>
    </v-row>
</template>