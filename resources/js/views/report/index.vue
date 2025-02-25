<script lang="ts" setup>
import { ref, onMounted, watch } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import IssueList from '@/views/step/IssueList.vue';
import ImageUploader from "@/app/common/components/ImageUploader.vue";
import Map from '@/views/step/Map.vue';
import { useLoading } from 'vue3-loading-overlay';
import { useToast } from 'vue-toast-notification';

const toast = useToast();
const loader = useLoading();
const route = useRoute();
const router = useRouter();
const steps = ref([]);
const footer = ref('');
const step = ref(0);
const submitData = ref({});
const form_id = route.params.id;
const issue_id = ref('');
const workspace_id = ref('');
const location = ref({});
const place_address = ref('');
const nextButtonText = ref('NEXT');
const isPrevDisabled = ref(true);
const previewContent = ref([]);

const nextStep = async () => {
    step.value ++;
    if(step.value == steps.value.length) {
        const response = await axios.post('/api/report', {issue_id: issue_id.value, content: JSON.stringify(submitData.value), status: 'accept', access: true});
        if(response) {
            toast.success('Issue reported successfully.', {position: 'top-right'})
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
}

const selectIssue = (data: Object) => {
    issue_id.value = data.id;
    submitData.value['issue_name'] = data.name
}

const saveFiles = (files: any) => {
    submitData.value['files'] = files.map(file => file.path);
}

watch(() => submitData.value, (newValue) => {
    const updatedPreview = { ...previewContent.value };
    Object.keys(newValue).forEach(key => {
        if (!['location', 'place_name', 'address', 'issue_name', 'files'].includes(key)) {
            if (newValue[key] !== '') updatedPreview[key] = newValue[key];
        }
    });
    previewContent.value = updatedPreview;
}, { deep: true });


onMounted(async () => {
    loader.show({
        canCancel: false,
        loader: 'dots',
        opacity: 1
    })
    const response = await axios.get(`/api/form/getActive/${form_id}`);
    if(response) {
        workspace_id.value = response.data.workspace_id;
        const workspace = await axios.get(`/api/workspace/meta/${workspace_id.value}`)
        if(workspace) {
            place_address.value = workspace.data.place_address;
            location.value = JSON.parse(workspace.data.location);
            submitData.value['place_name'] = workspace.data.place_name;
        }
        footer.value = response.data.footer;
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
        loader.hide();
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
                        <ImageUploader @update:modelValue="saveFiles" v-if="field.type == 'File Uploader'" style="z-index: 100;"/>
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
                                    :value="item.title"/>
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
                    <div v-html="footer" class="text-center mb-5"></div>
                </div>
            </v-col> 
            <v-col cols="5" class="field-preview">
                <div class="step-preview">
                    <div class="preview-header">
                        <h1 class="preview-category">{{ submitData['issue_name'] }}</h1>
                        <p class="preview-location">{{ submitData['address'] }}</p>
                    </div>
                    <v-divider class="preview-divider"></v-divider>
                    <div class="preview-content" v-for="(item, key) in previewContent" :key="key">
                        <h1>{{ key }}</h1>
                        <p>{{ item }}</p>
                    </div>
                    <v-divider class="preview-divider"></v-divider>
                    <div class="preview-footer">
                        <div class="preview-footer-item">
                            <v-icon>mdi-eye-circle-outline</v-icon>
                            <span>Private</span>
                        </div>
                        <div class="preview-footer-item">
                            <v-icon>mdi-city-variant-outline</v-icon>
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