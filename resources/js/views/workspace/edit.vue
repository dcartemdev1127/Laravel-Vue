<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import { useToast } from 'vue-toast-notification';
import Swal from 'sweetalert2';
import draggable from 'vuedraggable';
import GoogleAddressAutocomplete from 'vue3-google-address-autocomplete';

const toast = useToast();
const router = useRouter();
const route = useRoute();
const workspace_id = route.query.id;
const name = ref('');
const status = ref(true);
const categories = ref([]);
const isShow = ref(false);
const cat_name = ref('');
const cat_status = ref(true);
const place_address = ref('');
const place_name = ref('');
const location = ref({});

const handleSave = async () => {
    const response = await axios.post(`/api/workspace/${workspace_id}`, {name: name.value, status: status.value, place_name: place_name.value, place_address: place_address.value, location: location.value});
    categories.value.map(async (item, index) => {
        await axios.post(`/api/category/${item.id}`, {order: index+1});
    });
    if(response) {
        router.push({path: '/workspace'});
        toast.success('Workspace updated successfully.', {position: 'top-right'});
    }
}

const handleCreate = async () => {
    const response = await axios.post('/api/category', {name: cat_name.value, workspace_id: workspace_id, status: cat_status.value});
    if(response) {
        toast.success('Create category successfully', {position: 'top-right'});
        categories.value = categories.value.concat(response.data);
        isShow.value = false;
        cat_name.value = '';
        cat_status.value = true;
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
            const response = await axios.delete(`/api/category/${id}`);
            if(response) {
                categories.value = categories.value.filter(item => item.id != id);
            }
            Swal.fire("Deleted!", "Category deleted successfully.", "success");
        }
    });
}

const callbackFunction = (place) => {
    place_name.value = place.name;
    place_address.value = place.formatted_address;
    location.value = {lat: place.geometry.location.lat(), lng: place.geometry.location.lng()};
}

onMounted(async () => {
    const response = await axios.get(`/api/workspace/${workspace_id}`);
    if(response) {
        name.value = response.data.name;
        status.value = !!response.data.status;
        categories.value = response.data.categories;
        place_address.value = response.data.place_address;
        location.value = response.data.location;
    }
})
</script>

<template>
    <v-row justify="space-between" class="mt-5">
        <v-btn
            class="ml-5 mt-5"
            color="primary"
            @click="router.push({path: '/workspace'})"
        >
            BACK
        </v-btn>
        <v-btn
            class="mr-5 mt-5"
            color="primary"
            @click="handleSave()"
        >
            SAVE
        </v-btn>
    </v-row>
    <v-row>
        <v-col cols="12" lg="6">
            <Card title="Edit Workspace" class="h-100">
                <v-card-text>
                    <div class="font-weight-bold mb-2 mt-3">Name <i class="ph-asterisk ph-xs text-danger" /></div>
                    <TextField
                        v-model="name"
                        hide-details
                        placeholder="Enter workspace name"
                    />
                    <div class="font-weight-bold mb-2 mt-3">Location <i class="ph-asterisk ph-xs text-danger" /></div>
                    <GoogleAddressAutocomplete
                        apiKey="AIzaSyANKjpCtaUho8oy53T63IFl75Ia9qrdDlI"
                        @callback="callbackFunction"
                        class="search-location"
                        placeholder="Search Place Name or Address"
                        v-model="place_address"
                        :value="place_address"
                    />
                    <v-row class="mt-1">
                        <v-col cols="2">
                            <div class="font-weight-bold mb-2 mt-3">Status <i class="ph-asterisk ph-xs text-danger" /></div>
                        </v-col>
                        <v-col cols='10'>
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
                            Add Category
                        </v-btn>
                    </div>
                    <div>
                        <v-list>
                            <draggable v-model="categories" tag="div" item-key="id" handle=".drag-handle" animation="300">
                                <template #item="{ element }">
                                <v-list-item class="border rounded-lg mb-2" >
                                    <template v-slot:prepend>
                                    <v-icon class="drag-handle" style="cursor: grab;">mdi-drag</v-icon>
                                    </template>
                                    <v-list-item-content>
                                    <v-list-item-title>
                                        <div class="d-flex justify-space-between">
                                            <div class="d-flex">{{ element.name }}</div>
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
                                                @click="router.push({path: '/category', query: {id: element.id, workspace_id: workspace_id}})">
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
                            <v-list-item v-if="categories.length === 0">
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
                <h5 class="text-h6 font-weight-bold">Add category</h5>
            </v-card-title>
            <v-card-text>
                <div class="font-weight-bold mb-2 mt-3">Name <i class="ph-asterisk ph-xs text-danger" /></div>
                <TextField
                    v-model="cat_name"
                    hide-details
                    placeholder="Enter category name"
                />
                <v-row class="mt-1">
                    <v-col cols="2">
                        <div class="font-weight-bold mb-2 mt-3">Status <i class="ph-asterisk ph-xs text-danger" /></div>
                    </v-col>
                    <v-col cols="10">
                        <v-switch
                            v-model="cat_status"
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
.search-location {
    width: 100%;
    height: 42px;
    padding-left: 12px;
    padding-right: 12px;
    font-size: 13px;
    font-weight: 400;
    line-height: 20px;
    border: 1px solid rgba(0,0,0,0.12);
    border-radius: .25rem;
}
.search-location :focus {
    border: 1px solid rgba(0,0,0,0.12);
    border-radius: .25rem;
}
</style>