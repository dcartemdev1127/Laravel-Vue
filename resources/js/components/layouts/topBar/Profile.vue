<script lang="ts">
import { brandsList } from "@/components/layouts/utils";

export default {
  data() {
    return {
      brandsList
    };
  }
};
</script>
<template>
  <v-menu width="175">
    <template v-slot:activator="{ props }">
      <a dark v-bind="props" class="d-flex align-center mx-3">
        <v-avatar size="small" class="user-profile">
          <v-img
            class="header-profile-user"
            src="@/assets/images/users/avatar-1.jpg"
            alt="Header Avatar"
          />
        </v-avatar>
        <span class="text-start ms-xl-3">
          <h4
            class="d-none d-xl-inline-block user-name-text font-weight-medium"
          >
            Richard Marshall
          </h4>
          <span class="d-none d-xl-block user-name-sub-text">Founder</span>
        </span>
      </a>
    </template>
    <v-list density="compact" :lines="false" class="profile-list" nav>
      <h6 class="dropdown-header">Welcome Richard!</h6>
      <v-list-item class="dropdown-item" @click="">
        <i class="mdi mdi-account-circle text-muted" />
        Profile
      </v-list-item>
      <v-list-item class="dropdown-item" @click="">
        <i class="mdi mdi-message-text-outline text-muted" />
        Messages
      </v-list-item>
      <v-list-item class="dropdown-item" @click="">
        <i class="mdi mdi-calendar-check-outline text-muted" />Task board
      </v-list-item>
      <v-list-item class="dropdown-item" @click="">
        <i class="mdi mdi-lifebuoy text-muted" />Help
      </v-list-item>
      <v-divider class="my-2" />
      <v-list-item class="dropdown-item" @click="" to="/pages/profile">
        <div class="d-flex justify-space-between">
          <div>
            <i class="mdi mdi-wallet text-muted" />
            Balance
          </div>
          <span><b>$8451.36</b></span>
        </div>
      </v-list-item>
      <v-list-item class="dropdown-item" @click="" to="/pages/profile-settings">
        <div class="d-flex justify-space-between">
          <div>
            <i class="mdi mdi-cog-outline text-muted" />
            <span class="align-middle">Settings</span>
          </div>
          <v-chip color="success" label size="small"> New </v-chip>
        </div>
      </v-list-item>
      <v-list-item class="dropdown-item" @click="" to="/auth/lockscreen">
        <i class="mdi mdi-lock text-muted" />
        <span class="align-middle">Lock screen</span>
      </v-list-item>
      <v-list-item class="dropdown-item" @click="" to="/logout">
        <i class="mdi mdi-logout text-muted" />
        <span class="align-middle" data-key="t-logout">Logout </span>
      </v-list-item>
    </v-list>
  </v-menu>
</template>
