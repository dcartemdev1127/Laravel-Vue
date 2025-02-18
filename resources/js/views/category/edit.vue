<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const category_id = route.query.id;
const name = ref('');
const status = ref(true);
const issues = ref([]);

const handleSave = () => {
    router.push({path: '/category'})
}

onMounted(async () => {
    const response = await axios.get(`/api/category/${category_id}`);
    if(response) {
        name.value = response.data.name;
        status.value = response.data.status;
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
            <div class="font-weight-medium mb-1 mt-5">
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
            <v-switch
                v-model="status"
                color="primary"
                label="Status"
                hide-details
                >
            </v-switch>
        </v-col>
        <v-col col="12">
            <v-btn
                @click="router.push({path: '/category', query: {id: category_id}})"
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
                                    <v-btn icon="mdi-pencil-outline" size="small" @click="handleEdit(item.id)"></v-btn>
                                    <v-btn icon="mdi-trash-can-outline" size="small" @click="handleDelete(item.id)"></v-btn>
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
</template>