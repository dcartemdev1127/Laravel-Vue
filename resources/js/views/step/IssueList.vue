<script lang="ts" setup>
import { ref, onMounted, watch, defineProps, defineEmits } from 'vue';
import axios from "@/app/http/axios";

const query = ref('');
const issues = ref([]);
const filteredIssues = ref([]);
const selectItem = ref('');
defineProps({placeholder: String});
const emit = defineEmits(['select_issue']);

watch(query, (newQuery: string) => {
    const val = newQuery.toLocaleLowerCase();
    filteredIssues.value = issues.value.filter(issue => {
        if(issue.title.toLocaleLowerCase().includes(val)) return issue;
    });
});

const selectIssue = (data: Object) => {
    selectItem.value = data.id;
    emit('select_issue', data);
}

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
        :placeholder="placeholder"
        hide-details
        variant="solo"
        density="compact"
        class="filter-search text-field-component search-text"
    >
        <template #prepend-inner>
        <i class="ph-magnifying-glass text-muted search-icon" />
        </template>
    </v-text-field>

    <v-list
        v-model="selectItem"
        density="compact"
        nav
        class="issue-list"
        :lines="false" >
        <v-list-item
            v-for="item in filteredIssues"
            :key="item.id"
            density="compact"
            class="issue-list-item"
            :active="item.id == selectItem"
            @click="selectIssue({id: item.id, name: item.title})">
            <div class="list-item-text">
                {{ item.title }}
            </div>
        </v-list-item>
    </v-list>
    
</template>

<style>
.v-field__prepend-inner {
    margin-top: 8px !important;
}
/* .v-field {
    box-shadow: 0 4px 2px -2px #c5c5d0;
} */
.search-text {
    margin-bottom: 1rem;
    margin-left: 1rem;
    margin-right: 1rem;
    height: 50px;
}
.search-text input {
    font-size: 1rem;
    font-weight: 400;
    font-family: Arial, Helvetica, sans-serif;
}
.search-icon {
    width: 1.95rem;
    height: 1.2rem;
    font-size: 1.75rem;
    top: 50%;
    transform: translateY(-50%);
}
.issue-list {
    padding: unset;
    overflow: hidden;
}
.issue-list-item {
    margin: unset !important;
    border-bottom: 1px solid #c5c5d0;
    border-radius: unset;
    height: 54px;
}
.list-item-text {
    padding: 1rem;
    color: #080401;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 400;
    font-size: 1rem;
}
</style>