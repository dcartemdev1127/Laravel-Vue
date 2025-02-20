<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { useToast } from 'vue-toast-notification';
import Swal from 'sweetalert2';

const toast = useToast();
const isShow = ref(false);
const headers = ref([
    {key: 'name', title: 'Name', sortable: false},
    {ley: 'action', title: 'Action'}
]);
const selectId = ref('');
const name = ref('');
const dep_name = ref('');
const departments = ref([]);

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
            const response = await axios.delete(`/api/department/${id}`);
            if(response) {
                departments.value = departments.value.filter(item => item.id != id);
            }
            Swal.fire("Deleted!", "Department deleted successfully.", "success");
        }
    });
};

const handleUpdate = async (id: string) => {
    const response = await axios.post(`/api/department/${id}`, {name: dep_name.value});
    if(response) {
        departments.value = departments.value.map(item => item.id == id ? {...item, name: dep_name.value} : item);
        isShow.value= false;
        toast.success('Department updated successfully.', {position: 'top-right'});
    }
};

const getItem = async (id: string) => {
    const response = await axios.get(`/api/department/${id}`);
    if(response) {
        dep_name.value = response.data.name;
        isShow.value = true;
        selectId.value = response.data.id;
    }
}

const handleCreate = async () => {
    const response = await axios.post('/api/department', {name: name.value});
    if(response) {
        departments.value = departments.value.concat(response.data);
        name.value = '';
        toast.success('Department created successfully.', {position: 'top-right'});
    }
}

onMounted( async () => {
    const response = await axios.get('/api/department');
    if(response) {
        departments.value = response.data;
    }
});

</script>

<template>
    <v-row justify="space-around" class="mt-5">
        <v-col cols="12" lg="5">
            <v-data-table
                :headers="headers"
                :items="departments"
                style="border-radius: 12px;"
                class="customTable"
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
                                <v-btn icon="mdi-pencil-outline" size="small" @click="getItem(item.id)"></v-btn>
                                <v-btn icon="mdi-trash-can-outline" size="small" @click="handleDelete(item.id)"></v-btn>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-col>
        <v-col cols="12" lg="5">
            <Card title="Add Department" class="h-100">
                <v-card-text>
                    <div class="font-weight-bold mb-2 mt-3">Department Name</div>
                    <TextField
                        v-model="name"
                        hide-details
                        placeholder="Enter department name"
                    />
                    <div class="d-flex justify-end mt-5">
                        <v-btn color="primary" @click="handleCreate()">ADD</v-btn>
                    </div>
                </v-card-text>
            </Card>
        </v-col>
    </v-row>
    <v-dialog v-model="isShow" class="w-50">
        <Card>
            <v-card-title class="text-center">
                <h1>Edit Department</h1>
            </v-card-title>
            <v-card-text>
                <div class="font-weight-bold mb-2 mt-3">Department Name</div>
                <TextField
                    v-model="dep_name"
                    hide-details
                    placeholder="Enter department name"
                />
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions class="d-flex justify-end">
                <v-btn @click="isShow = true">Cancel</v-btn>
                <v-btn color="primary" @click="handleUpdate(selectId)">Save</v-btn>
            </v-card-actions>
        </Card>
    </v-dialog>
</template>
<style>
.customTable th:last-child, td:last-child {
    text-align: center !important;
    width: 150px;
}
</style>