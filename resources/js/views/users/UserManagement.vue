<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { cities, roles } from '@/app/const';
import { useToast } from 'vue-toast-notification';
import Swal from 'sweetalert2';

const toast = useToast();
const isShow = ref(false);
const selectId = ref('');
const loadingTable = ref(false);
const headers = ref([
    {key: 'name', title: 'Name', sortable: true},
    {key: 'city', title: 'City', sortable: true},
    {key: 'department', title: 'Department', sortable: true},
    {key: 'role', title: 'Role', sortable: true},
    {key: 'action', title: 'Action'}
]);

const users = ref([]);
const departments = ref([]);
const username = ref('');
const city = ref('');
const department = ref('');
const role = ref('');

const handleUpdate = async (id: string) => {
    const response = await axios.get(`/api/users/${id}`);
    if(response) {
        isShow.value = true;
        selectId.value = id;
        username.value = response.data.username;;
        city.value = response.data.city;
        department.value = response.data.department_id;
        role.value = response.data.role;
    }
};

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
            const response = await axios.delete(`/api/users/${id}`);
            if(response) {
                users.value.filter(item => item.id != id);
            }
            Swal.fire("Deleted!", "User delete successfully.", "success");
        }
    });
};

const updateUser = async () => {
    const data = {
        username: username.value,
        department_id: department.value,
        city: city.value,
        role: role.value
    }
    const response = await axios.post(`/api/users/${selectId.value}/update`, data)
    if(response) {
        isShow.value = false;
        toast.success('User update successfully.', {position: 'top-right'});
        users.value = users.value.map(item => item.id == selectId.value ? {...item, ...data} : item);
    }
}

onMounted(async () => {
    loadingTable.value = true;
    const response = await axios.get('/api/users');
    if(response) {
        users.value = response.data;
        loadingTable.value = false;
    }
    const depts = await axios.get('/api/department');
    if(depts) {
        departments.value = depts.data;
    }
})
</script>

<template>
    <v-row justify="space-around" class="mt-5">
        <v-col cols="12">
            <v-data-table
                :headers="headers"
                :items="users"
                style="border-radius: 12px;"
                :loading="loadingTable"
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
                        <td>{{ item.username }}</td>
                        <td>{{ item.city }}</td>
                        <td>{{ item.department.name }}</td>
                        <td>{{ item.role }}</td>
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
    </v-row>
    <v-dialog
        class="w-50"
        v-model="isShow"
    >
        <Card>
            <v-card-title class="text-center">
                <h5 class="text-h6 font-weight-bold">Update User Information</h5>
            </v-card-title>
            <v-card-text class="mt-5">
                <div class="font-weight-bold mb-2 mt-3">Username</div>
                <TextField
                    v-model="username"
                    hide-details
                    placeholder="Enter department name"
                />
                <div class="font-weight-bold mb-2 mt-3">Role</div>
                <v-select
                    label="Select your role"
                    variant="outlined"
                    :items="roles"
                    density="compact"
                    isReuired
                    v-model="role"
                />
                <div class="font-weight-bold mb-2 mt-3">City</div>
                <v-select
                    label="Select your city"
                    variant="outlined"
                    :items="cities"
                    density="compact"
                    isReuired
                    v-model="city"
                />
                <div class="font-weight-bold mb-2 mt-3">Department</div>
                <v-select
                    label="Select your department"
                    variant="outlined"
                    :items="departments"
                    item-title="name"
                    item-value="id"
                    density="compact"
                    v-model="department"
                />
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
                    text="Update"
                    variant="tonal"
                    @click="updateUser()"
                ></v-btn>
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