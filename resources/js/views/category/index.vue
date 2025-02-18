<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import {useToast} from 'vue-toast-notification';

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

const handleSave = async () => {
    const response = await axios.post(`/api/category/${category_id}`, {name: name.value, status: status.value});
    if(response) {
        router.push({path: '/edit_workspace', query: {id: workspace_id}});
        toast.success('Category updated successfully.', {position: 'top-right'});
    }
}

const handleCreate = async () => {
    const response = await axios.post('/api/issue', {name: issue_name.value, category_id: category_id});
    if(response) {
        toast.success('Create issue successfully', {position: 'top-right'});
        issue_name.value = '';
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
    const response = await axios.get(`/api/category/${category_id}`);
    if(response) {
        name.value = response.data.name;
        status.value = !!response.data.status;
        issues.value = response.data.issues;
    }
})
</script>

<template>
    <v-row justify="end" class="mt-5">
        <v-btn
            class="mr-5"
            color="blue"
            @click="handleSave()"
        >
            SAVE
        </v-btn>
    </v-row>
    <v-row>
        <v-col cols="12">
            <div class="font-weight-medium mt-5">
                Name <i class="ph-asterisk ph-xs text-danger" />
            </div>
        </v-col>
        <v-col cols="12">
            <v-text-field
                v-model="name"
                isRequired
                placeholder="Enter category name"
                hideDetails
            ></v-text-field>
        </v-col>
        <v-col cols="12">
            <div class="font-weight-medium mt-5">
                Status <i class="ph-asterisk ph-xs text-danger" />
            </div>
        </v-col>
        <v-col cols="12">
            <v-switch
                v-model="status"
                color="primary"
                :label="status ? 'Enable' : 'Disable'"
                hide-details
                >
            </v-switch>
        </v-col>
        <v-col col="12">
            <v-btn
                @click="isShow = true"
                color="blue"
            >
                Add Issue
            </v-btn>
        </v-col>
        <v-col cols="12">
            <v-card
                max-width="300"
            >
                <v-list density="compact">
                    <v-list-subheader>Issue List</v-list-subheader>
                    <template v-if="issues.length">
                        <v-list-item
                            v-for="(item, i) in issues"
                            :key="i"
                            :value="item"
                            color="primary"
                        >
                            <v-list-item-title v-text="item.name"></v-list-item-title>
                            <template v-slot:append>
                                <div>
                                    <v-btn 
                                        icon="mdi-pencil-outline" 
                                        size="small" 
                                        @click="router.push({path: '/issue', query: {id: item.id, category_id: category_id, workspace_id: workspace_id}})">
                                    </v-btn>
                                    <v-btn 
                                        icon="mdi-trash-can-outline" 
                                        size="small" 
                                        @click="handleDelete(item.id)">
                                    </v-btn>
                                </div>
                            </template>
                        </v-list-item>
                    </template>
                    <v-list-item v-else>
                        <v-list-item-title class="text-center text-grey">
                            No data available
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-card>
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
            <v-card-text class="mt-5">
                <v-row justify="center" class="align-center">
                    <v-col cols="12">
                        <div class="font-weight-medium mb-2 mt-5">
                            Issue Name <i class="ph-asterisk ph-xs text-danger" />
                        </div>
                        <v-text-field
                            v-model="issue_name"
                            isRequired
                            placeholder="Enter issue name"
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