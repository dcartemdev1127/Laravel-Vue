<script lang="ts" setup>
import { ref, onMounted, computed } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import draggable from "vuedraggable";
import ImageUploader from "@/app/common/components/ImageUploader.vue";
import IssueList from '@/views/step/IssueList.vue';

const form = ref([]);
const step = ref(1);
const defaultModel = ref('');

const disabled = computed(() => step.value === 1 ? 'prev' : step.value === 3 ? 'next' : undefined);

onMounted(async () => {
    const response = await axios.get('/api/form/1');
    if(response) {
        form.value = response.data;
    }
});
</script>

<template>
    <v-row justify="center">
        <v-col cols="9">
            <v-row style="min-height: calc(100vh - 140px); margin-top: 7px;">
                <v-col cols="8">
                    <Card>
                        <v-card-text>
                            <v-stepper v-model="step" v-if="form.steps">
                                <template v-slot:default="{ prev, next }">
                                    <v-stepper-header>
                                    <template v-for="n in form.steps.length" :key="`${n}-step`">
                                        <v-stepper-item
                                            :complete="step > n"
                                            :step="`Step {{ n }}`"
                                            :value="n"
                                        ></v-stepper-item>
                                        <v-divider
                                            v-if="n !== form.steps.length"
                                            :key="n"
                                        ></v-divider>
                                    </template>
                                    </v-stepper-header>
                                    <v-stepper-window>
                                    <v-stepper-window-item
                                        v-for="n in form.steps.length"
                                        :key="`${n}-content`"
                                        :value="n"
                                    >
                                        <v-card v-if="form.steps && form.steps.length >= n">
                                            <div v-for="field in form?.steps[n-1]?.fields" :key="field.id">
                                                <div class="font-weight-bold mb-1">{{field.label}}</div>
                                                <v-text-field
                                                    v-if="field.type == 'text'"
                                                    hide-details
                                                    variant="solo"
                                                    density="compact"
                                                    class="text-field-component"
                                                    :placeholder="field.placeholder || 'text input'"
                                                />
                                                <v-text-field
                                                    v-if="field.type == 'email'"
                                                    hide-details
                                                    variant="solo"
                                                    :placeholder="field.placeholder || 'example@gmail.com'"
                                                    density="compact"
                                                    class="text-field-component"
                                                >
                                                    <template #prepend-inner>
                                                    <i class="ph-envelope pe-3" />
                                                    </template>
                                                </v-text-field>
                                                <v-textarea
                                                    v-if="field.type == 'textarea'"
                                                    hide-details
                                                    variant="solo"
                                                    density="compact"
                                                    :placeholder="field.placeholder || 'textarea'"
                                                    class="text-field-component"
                                                />
                                                <VueDatePicker
                                                    v-if="field.type == 'datetime'"
                                                    v-model="defaultModel"
                                                    :teleport="true"
                                                    placeholder="Select date"
                                                />
                                                <ImageUploader v-if="field.type == 'file'"/>
                                                <v-rating
                                                    v-if="field.type == 'rating'"
                                                    density="comfortable"
                                                    clearable
                                                    :model-value="3"
                                                    active-color="warning"
                                                />
                                                <v-select
                                                    v-if="field.type == 'select'"
                                                    :label="field.placeholder"
                                                    variant="outlined"
                                                    :items="field.options"
                                                    density="compact"
                                                    class="mt-2"
                                                />
                                                <v-checkbox
                                                    v-if="field.type == 'checkbox'"
                                                    :model-value="true"
                                                    color="primary"
                                                    hide-details
                                                    density="compact"
                                                >
                                                    <template #label><span>{{ field.placeholder || 'checkbox' }}</span></template>
                                                </v-checkbox>
                                                <v-radio-group 
                                                    v-if="field.type == 'radio'" 
                                                    :model-value="field.radios[0]" 
                                                    color="primary"
                                                >
                                                    <v-radio 
                                                    v-for="radio in field.radios" 
                                                    density="compact" 
                                                    :value="radio"
                                                    >
                                                    <template #label><span>{{ radio }}</span></template>
                                                    </v-radio>
                                                </v-radio-group>
                                                <v-list v-if="field.type == 'list'" :items="field.items"></v-list>
                                                <IssueList v-if="field.type == 'issues'"/>
                                            </div>
                                        </v-card>
                                    </v-stepper-window-item>
                                    </v-stepper-window>

                                    <v-stepper-actions
                                        :disabled="disabled"
                                        @click:next="next"
                                        @click:prev="prev"
                                    ></v-stepper-actions>
                                </template>
                            </v-stepper>
                        </v-card-text>
                    </Card>
                </v-col>
                <v-col cols="4">
                    <Card>
                        <v-card-text>

                        </v-card-text>
                    </Card>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>