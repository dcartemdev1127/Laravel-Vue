<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import {useToast} from 'vue-toast-notification';

const toast = useToast();
const router = useRouter();
const route = useRoute();
const step_id = route.query.id;
const form_id = route.query.form_id;
const name = ref('');
const status = ref(true);

const handleSave = async () => {
    const response = await axios.post(`/api/step/${step_id}`, {name: name.value, status: status.value});
    if(response) {
        router.push({path: '/form', query: {id: form_id}});
        toast.success('step updated successfully.', {position: 'top-right'});
    }
}

onMounted(async () => {
    const response = await axios.get(`/api/step/${step_id}`);
    if(response) {
        name.value = response.data.name;
        status.value = !!response.data.status;
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
                placeholder="Enter step name"
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
    </v-row>
</template>