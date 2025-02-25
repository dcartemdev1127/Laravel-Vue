<script lang="ts" setup>
import { socialMedias } from "@/components/auth/utils";
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { UserType } from "@/app/http/types";
import axios from "@/app/http/axios";

const router = useRouter();

const loading = ref(false);
const isSubmitted = ref(false);
const isRemember = ref(false);
const errorMsg = ref("");
const formData = ref({
  username: { value: "", isValid: true },
  password: { value: "", isValid: true },
});

const isValidFormData = computed(() => {
  return formData.value.username.isValid && formData.value.password.isValid;
});

const onSignIn = async () => {
  try {
    loading.value = true;
    errorMsg.value = "";
    isSubmitted.value = true;
    if (!isValidFormData.value) {
      return;
    }

    const { password, username } = formData.value;
    const payload: UserType = {
      password: password.value,
      username: username.value,
    };

    const response = await axios.post('/api/login', payload);
    if(response) {
      localStorage.setItem('jwtToken', response.data.data.access_token);
      router.push({path: '/'});
    }

  } catch (error: any) {
    errorMsg.value = error.message;
  } finally {
    loading.value = false;
  }
};
</script>
<template>
  <div class="h-100 d-flex align-center justify-center">
    <div class="w-100">
      <v-card-title class="text-center">
        <h5 class="text-h6 font-weight-bold mt-10">Welcome Back</h5>
        <div class="text-muted font-weight-regular">
          Sign in to continue to Steex.
        </div>
      </v-card-title>
      <v-card-text class="mt-5">
        <v-row justify="center" class="align-center">
          <v-col cols="12" lg="8">
            <v-alert
              v-if="errorMsg"
              class="mb-3"
              color="danger"
              variant="tonal"
              density="compact"
            >
              {{ errorMsg }}
            </v-alert>
            <div class="font-weight-medium mb-1">
              Username <i class="ph-asterisk ph-xs text-danger" />
            </div>
            <TextField
              v-model="formData.username"
              :value="formData.username.value"
              isRequired
              :showError="isSubmitted"
              :isSubmitted="isSubmitted"
              hideDetails
              placeholder="Enter username"
            />
            <div class="d-flex justify-space-between align-center mt-4">
              <div class="font-weight-medium">
                Password <i class="ph-asterisk ph-xs text-danger" />
              </div>
              <v-btn
                variant="plain"
                class="px-0 font-weight-regular"
                to="/pass-reset"
              >
                Forgot password?
              </v-btn>
            </div>
            <TextField
              v-model="formData.password"
              :value="formData.password.value"
              placeholder="Enter password"
              hide-details
              :showError="isSubmitted"
              :isSubmitted="isSubmitted"
              isRequired
              isPassword
            />
            <v-checkbox
              v-model="isRemember"
              hide-details
              color="primary"
              class="my-1"
            >
              <template #label>
                <span>Remember me</span>
              </template>
            </v-checkbox>
            <v-btn
              color="primary"
              block
              class="mt-2"
              :loading="loading"
              @click="onSignIn"
            >
              Sign In
            </v-btn>
            <div class="d-flex align-center mt-10">
              <v-divider class="border-dashed" />
              <v-divider class="border-dashed" />
            </div>

            
            <div class="text-center mt-8 d-flex align-center justify-center">
              Don't have an account?
              <v-btn
                to="/signup"
                variant="text"
                color="secondary"
                class="font-weight-bold text-decoration-underline pa-0"
              >
                SignUp
              </v-btn>
            </div>
          </v-col>
        </v-row>
      </v-card-text>
    </div>
  </div>
</template>
