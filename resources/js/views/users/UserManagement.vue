<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { cities } from '@/app/const';

const errorMsg = ref('');
const successMsg = ref('');
const isShow = ref(false);
const selectId = ref('');
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
        department.value = response.data.department.name;
        role.value = response.data.role;
    }
};

const handleDelete = async (id: string) => {
    const response = await axios.delete(`/api/users/${id}`);
    if(response) {
        users.value.filter(item => item.id != id);
    }
};

const updateUser = async () => {
    const data = {
        username: username.value,
        department: department.value,
        city: city.value,
        role: role.value
    }
    const response = await axios.post(`/api/users/${selectId.value}/update`, data)
    if(response) {
        isShow.value = false;
    }
}

onMounted(async () => {
    const response = await axios.get('/api/users');
    if(response) {
        users.value = response.data;
    }
    const depts = await axios.get('/api/department');
    if(depts) {
        departments.value = depts.data;
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
        v-model="isShow"
    >
        <v-card>
            <v-card-title class="text-center">
                <h5 class="text-h6 font-weight-bold">Update User Information</h5>
            </v-card-title>
            <v-card-text class="mt-5">
                <v-row justify="center" class="align-center">
                <v-col cols="12" lg="8">
                    <v-alert
                    v-if="errorMsg"
                    class="mb-3"
                    color="danger"
                    variant="tonal"
                    density="compact"
                    >
                    {{ errorMsg }}
                    </v-alert>

                    <v-alert
                    v-if="successMsg"
                    class="mb-3"
                    color="success"
                    variant="tonal"
                    density="compact"
                    >
                    {{ successMsg }}
                    </v-alert>
                    <div class="font-weight-medium mb-2 mt-5">
                    Username <i class="ph-asterisk ph-xs text-danger" />
                    </div>
                    <TextField
                    v-model="username"
                    isRequired
                    placeholder="Enter username"
                    hide-details
                    />
                    <div class="font-weight-medium mb-2 mt-5">
                    City <i class="ph-asterisk ph-xs text-danger" />
                    </div>
                    <v-select
                    label="Select your city"
                    variant="outlined"
                    :items="cities"
                    density="compact"
                    class="mt-2"
                    isReuired
                    v-model="city"
                    />
                    <div class="font-weight-medium mb-2 mt-5">
                    Department <i class="ph-asterisk ph-xs text-danger" />
                    </div>
                    <v-select
                    label="Select your department"
                    variant="outlined"
                    :items="departments"
                    item-title="name"
                    item-value="id"
                    density="compact"
                    class="mt-2"
                    v-model="department"
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
                    text="Update"
                    variant="tonal"
                    @click="updateUser()"
                ></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>