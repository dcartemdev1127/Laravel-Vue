<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import { GoogleMap, Marker } from 'vue3-google-map';

const center = { lat: 40.689247, lng: -74.044502 }
const locations = [
{ lat: 40.689247, lng: -74.044502 },
{ lat: 40.889247, lng: -74.344502 },
{ lat: 40.649247, lng: -74.094502 },
{ lat: 41.689247, lng: -73.044502 },
{ lat: 40.189247, lng: -74.344502 },
]

const router = useRouter();
const reports = ref([
    {
        id: 1,
        title: 'Highway Dept - Miscellaneous',
        location: '1634 Boulevard Ave Peekskill, New York, 10566',
        status: true,
        time: '2/10/25 9:57:36 AM',
        description: 'Plow went by and tore up front lawn on two oc'
    },
    {
        id: 2,
        title: 'Highway Dept- Approved Signs by Resolutions',
        location: 'Railroad Ave Peekskill, New York, 10566',
        status: true,
        time: '1/17/25 10:18:57 AM',
        description: 'Parking spot lines not visible, vehicles are not p'
    },
    {
        id: 3,
        title: 'Highway Dept- Approved Signs by Resolutions',
        location: 'Railroad Ave Peekskill, New York, 10566',
        status: true,
        time: '1/17/25 10:18:57 AM',
        description: 'Parking spot lines not visible, vehicles are not p'
    },
    {
        id: 4,
        title: 'Highway Dept- Approved Signs by Resolutions',
        location: 'Railroad Ave Peekskill, New York, 10566',
        status: true,
        time: '1/17/25 10:18:57 AM',
        description: 'Parking spot lines not visible, vehicles are not p'
    },
    {
        id: 5,
        title: 'Highway Dept- Approved Signs by Resolutions',
        location: 'Railroad Ave Peekskill, New York, 10566',
        status: true,
        time: '1/17/25 10:18:57 AM',
        description: 'Parking spot lines not visible, vehicles are not p'
    },
]);
const isDetail = ref(false);

const showDetail = (id: number) => {
    console.log(id);
    isDetail.value = true;
}

onMounted(async () => {
    // const response = await axios.get('/api/report');
    // if(response) {
    //     reports.value = response.data;
    // }
});
</script>

<template>
    <v-row style="overflow-y: hidden !important; height: calc(100vh - 145px); margin-top: unset !important;">
        <v-col data-simplebar cols="12" lg="4" md="4" style="overflow-y: auto !important; height: 100%;">
            <div v-if="!isDetail">
                <div v-for="item in reports" :key="item.id" class="report-list">
                    <div @click="showDetail(item.id)" class="report-title">{{ item.title }}</div>
                    <p class="report-location">{{ item.location }}</p>
                    <div class="report-state-time">
                        <div class="report-state">{{ item.status ? 'Active' : 'Unactive' }}</div>
                        <div class="report-time">
                            <v-icon>mdi-clock-time-two-outline</v-icon>
                            {{ item.time }}
                        </div>
                    </div>
                    <p class="report-description">{{ item.description }}</p>
                </div>
            </div>
            <div v-else>

            </div>
        </v-col>
        <v-col cols="12" lg="8" md="4" style="position: relative;">
            <GoogleMap
                api-key="AIzaSyANKjpCtaUho8oy53T63IFl75Ia9qrdDlI"
                style="width: 100%; height: 100%;"
                :center="center"
                :zoom="7"
            >
                <Marker v-for="(location, i) in locations" :key="i" :options="{ position: location }" />
            </GoogleMap>
            <v-btn
                @click="router.push({path: '/report'})"
                class="btn-report"
            >
                <v-icon>mdi-plus-circle-outline</v-icon>
                New Report
            </v-btn>
        </v-col>
    </v-row>
</template>

<style>

</style>