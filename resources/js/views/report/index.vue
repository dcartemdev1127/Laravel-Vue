<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import IssueList from '@/views/step/IssueList.vue';
import ImageUploader from "@/app/common/components/ImageUploader.vue";
import Map from '@/views/step/Map.vue';

const route = useRoute();
const router = useRouter();
const steps = ref([]);
const step = ref(0);
const submitData = ref({});
const form_id = route.params.id;
const issue_id = ref('');
const workspace_id = ref('');
const location = ref({});
const place_address = ref('');
const nextButtonText = ref('NEXT');
const isPrevDisabled = ref(true);
const nextStep = async () => {
    step.value ++;
    if(step.value == steps.value.length) {
        const response = await axios.post('/api/report', {issue_id: issue_id.value, content: JSON.stringify(submitData.value), status: 'accept', access: true});
        if(response) {
            router.push(`/${workspace_id.value}`);
        }
    }
    if(step.value == steps.value.length - 1) {
        nextButtonText.value = 'SUBMIT';
    }
    isPrevDisabled.value = false;
}

const prevStep = () => {
    step.value --;
    if(step.value == 0) {
        isPrevDisabled.value = true;
        return;
    }
    if(step.value < steps.value.length ) nextButtonText.value = 'NEXT';
}

const saveLocation = (location: Object) => {
    submitData.value['address'] = location.address;
    submitData.value['location'] = location.coords;
    console.log(submitData.value)
}

const selectIssue = (data: Object) => {
    issue_id.value = data.id;
    submitData.value['issue_name'] = data.name
}

const saveFiles = (files: any) => {
    submitData.value['files'] = files.map(file => file.path);
}

onMounted(async () => {
    const response = await axios.get(`/api/form/getActive/${form_id}`);
    if(response) {
        workspace_id.value = response.data.workspace_id;
        const workspace = await axios.get(`/api/workspace/meta/${workspace_id.value}`)
        if(workspace) {
            place_address.value = workspace.data.place_address;
            location.value = JSON.parse(workspace.data.location);
            submitData.value['place_name'] = workspace.data.place_name;
        }
        steps.value = response.data.steps;
        steps.value.forEach(step => {
            step.fields.forEach(field => {
                if (field.type !== 'Google Map' && field.type !== 'Issue List' && field.type !== 'File Uploader') {
                    submitData.value[field.name] = '';
                }
            });
        });
        submitData.value['address'] = '';
        submitData.value['issue_name'] = '';
    }
});
</script>

<template>
    <div class="main-form">
        <v-row>
            <v-col cols="7" class="step-form">
                <div>
                    <div class="field-title">
                        <p class="field-title-text">{{ steps[step]?.name }}</p>
                    </div>
                    <div v-for="field in steps[step]?.fields" :key="field.id">
                        <div class="field-label">{{field.label}}</div>
                        <v-text-field
                            v-if="field.type == 'Input'"
                            hide-details
                            variant="solo"
                            density="compact"
                            class="text-field-component"
                            :placeholder="field.placeholder || ''"
                            v-model="submitData[field.name]"
                        />
                        <v-text-field
                            v-if="field.type == 'Email Input'"
                            hide-details
                            variant="solo"
                            :placeholder="field.placeholder || ''"
                            density="compact"
                            class="text-field-component"
                            v-model="submitData[field.name]"
                        >
                            <template #prepend-inner>
                            <i class="ph-envelope pe-3" />
                            </template>
                        </v-text-field>
                        <v-textarea
                            v-if="field.type == 'Textarea'"
                            hide-details
                            variant="solo"
                            density="compact"
                            :placeholder="field.placeholder || ''"
                            class="text-field-component px-3"
                            v-model="submitData[field.name]"
                        />
                        <VueDatePicker
                            v-if="field.type == 'Datetime Picker'"
                            :teleport="true"
                            placeholder="Select date"
                            v-model="submitData[field.name]"
                        />
                        <Map 
                            v-if="field.type == 'Google Map'" 
                            :coords="location" 
                            :address="place_address" 
                            @confirm_location="saveLocation"
                        />
                        <ImageUploader @update:modelValue="saveFiles" v-if="field.type == 'File Uploader'"/>
                        <v-rating
                            v-if="field.type == 'Rating'"
                            density="comfortable"
                            clearable
                            :model-value="3"
                            active-color="warning"
                            v-model="submitData[field.name]"
                        />
                        <v-select
                            v-if="field.type == 'Select'"
                            :label="field.placeholder"
                            variant="outlined"
                            :items="field.options"
                            density="compact"
                            class="mt-2"
                            v-model="submitData[field.name]"
                        />
                        <v-checkbox
                            v-if="field.type == 'Checkbox'"
                            :model-value="true"
                            color="primary"
                            hide-details
                            density="compact"
                            v-model="submitData[field.name]"
                        >
                            <template #label><span>{{ field.placeholder || '' }}</span></template>
                        </v-checkbox>
                        <div v-if="field.type == 'Custom Radio'" v-for="(item, i) in field.radios" :key="i" class="custom-radio">
                            <div class="radio-item">
                            
                            <label :for="'radio-'+i" class="radio-text">
                                <p class="radio-title">{{ item.title }}</p>
                                <p class="radio-desc">{{ item.desc }}</p>
                            </label>
                            <div class="radio-check">
                                <input 
                                    :id="'radio-'+i" 
                                    type="radio" 
                                    v-model="submitData[field.name]"
                                    :value="i"/>
                            </div>
                            </div>
                        </div>
                        <v-list 
                            v-if="field.type == 'List'" 
                            :items="field.items" 
                            v-model="submitData[field.name]">
                        </v-list>
                        <IssueList 
                            v-if="field.type == 'Issue List'"
                            :placeholder="field.placeholder" 
                            @select_issue="selectIssue"
                        />
                        <p class="text-muted my-1 ml-1 px-3">{{field.description}}</p>
                    </div>
                    <v-divider></v-divider>
                    <v-card-actions class="d-flex justify-space-between">
                        <v-btn :disabled="isPrevDisabled" @click="prevStep">PREV</v-btn>
                        <v-btn :disabled="isNextDisabled" @click="nextStep">{{ nextButtonText }}</v-btn>
                    </v-card-actions>
                </div>
            </v-col> 
            <v-col cols="5" class="field-preview">
                <div class="step-preview">
                    <div class="preview-header">
                        <h1 class="preview-category">{{ submitData['issue_name'] }}</h1>
                        <p class="preview-location">{{ submitData['address'] }}</p>
                    </div>
                    <v-divider class="preview-divider"></v-divider>
                    <div class="preview-content">
                        <p>This is description</p>
                    </div>
                    <v-divider class="preview-divider"></v-divider>
                    <div class="preview-footer">
                        <div class="preview-footer-item">
                            <v-icon>ph-eye-thin</v-icon>
                            <span>Private</span>
                        </div>
                        <div class="preview-footer-item">
                            <v-icon>ph-eye-thin</v-icon>
                            <span>{{ submitData['place_name'] }}</span>
                        </div>
                    </div>
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<style>
</style>