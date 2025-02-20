<script lang="ts" setup>
import { ref, onMounted, watch } from 'vue';
import axios from "@/app/http/axios";

const query = ref('');
const loading = ref(false);
const issues = ref([]);
const filteredIssues = ref([]);
const selectItem = ref('');

watch(query, (newQuery: string) => {
    const val = newQuery.toLocaleLowerCase();
    filteredIssues.value = issues.value.filter(issue => {
        if(issue.title.toLocaleLowerCase().includes(val)) return issue;
    });
});

onMounted(async () => {
    const response = await axios.get('/api/category/withIssues');
    if(response) {
        issues.value = response.data;
        filteredIssues.value = response.data;
    }
})
</script>

<template>
    <v-text-field
        v-model="query"
        :loading="loading"
        hide-details
        variant="solo"
        density="compact"
        class="filter-search text-field-component"
    >
        <template #prepend-inner>
        <i class="ph-magnifying-glass text-muted" />
        </template>
    </v-text-field>

    <v-list
        v-model="selectItem"
        density="compact"
        nav
        :lines="false" >
        <v-list-item
            v-for="item in filteredIssues"
            :key="item.id"
            density="compact"
            :active="item.id == selectItem"
            class="border rounded-lg py-3 px-4 mb-2"
            @click="selectItem = item.id">
            <div class="font-weight-bold">
                {{ item.title }}
            </div>
        </v-list-item>
    </v-list>
    
</template>

<style>
.v-field__prepend-inner {
    margin-top: 5px !important;
}
</style>