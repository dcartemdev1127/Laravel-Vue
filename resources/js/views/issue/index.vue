<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import {useToast} from 'vue-toast-notification';

const toast = useToast();
const router = useRouter();
const route = useRoute();
const issue_id = route.query.id;
const category_id = route.query.category_id;
const workspace_id = route.query.workspace_id;
const name = ref('');
const status = ref(true);

const handleSave = async () => {
    const response = await axios.post(`/api/issue/${issue_id}`, {name: name.value, status: status.value});
    if(response) {
        router.push({path: '/category', query: {id: category_id, workspace_id: workspace_id}});
        toast.success('Issue updated successfully.', {position: 'top-right'});
    }
}

onMounted(async () => {
    const response = await axios.get(`/api/issue/${issue_id}`);
    if(response) {
        name.value = response.data.name;
        status.value = !!response.data.status;
    }
})
</script>

<template>
    <v-row justify="space-between" class="mt-5">
        <v-btn
            class="ml-5 mt-5"
            color="primary"
            @click="router.push({path: '/category', query: {id: category_id, workspace_id: workspace_id}})"
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
            <Card title="Edit Issue" class="h-100">
                <v-card-text>
                    <div class="font-weight-bold mb-2 mt-3">Name <i class="ph-asterisk ph-xs text-danger" /></div>
                    <TextField
                        v-model="name"
                        hide-details
                        placeholder="Enter issue name"
                    />
                    <v-row class="mt-1">
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
            </Card>
        </v-col>
    </v-row>
</template>