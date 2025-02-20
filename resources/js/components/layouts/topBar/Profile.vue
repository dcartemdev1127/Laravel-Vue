<script lang="ts" setup>
import { Avatar1 } from '@/assets/images/users/utils';

</script>
<template>
  <v-menu width="175">
    <template v-slot:activator="{ props }">
      <a dark v-bind="props" class="d-flex align-center mx-3">
        <v-avatar size="small" class="user-profile">
          <v-img
            :src=Avatar1
            class="header-profile-user"
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
      <v-list-item class="dropdown-item" @click="" to='/pages/profile'>
        <i class="mdi mdi-account-circle text-muted" />
        Profile
      </v-list-item>
      
      <v-list-item class="dropdown-item" @click="" to="/logout">
        <i class="mdi mdi-logout text-muted" />
        <span class="align-middle" data-key="t-logout">Logout </span>
      </v-list-item>
    </v-list>
  </v-menu>
</template>
