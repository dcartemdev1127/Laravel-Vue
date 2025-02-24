<script lang="ts" setup>
import { ref, onMounted, watch, computed } from 'vue';
import axios from "@/app/http/axios";
import { useRouter, useRoute } from 'vue-router';
import { GoogleMap, Marker } from 'vue3-google-map';
import { format } from 'date-fns';

const route = useRoute();
const router = useRouter();
const reports = ref([]);
const isDetail = ref(false);
const locations = ref([]);
const workspace_id = route.params.id;
const mapRef = ref(null);
const place_name = ref('');
const place_address = ref('');
const location = ref({ lat: 40.689247, lng: -74.044502 });
const bounds = ref(null);
const form_id = ref('');
const currentPage = ref(1);
const limit = 20;
const detailedReport = ref({});
const selectedImage = ref(null);

const openImage = (image) => {
  selectedImage.value = image;
};
const totalReports = computed(() => reports.value.length);
const totalPages = computed(() => Math.ceil(totalReports.value / limit));

const from = computed(() => {
  return (currentPage.value - 1) * limit + 1;
});

const to = computed(() => {
  const end = currentPage.value * limit;
  return end > totalReports.value ? totalReports.value : end;
});

const paginatedReports = computed(() => {
  const start = (currentPage.value - 1) * limit;
  return reports.value.slice(start, start + limit);
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const getBounds = async () => {
  const geocoder = new google.maps.Geocoder();
  
  geocoder.geocode({ address: place_address.value }, (results, status) => {
    if (status === "OK" && results[0]) {
        const placeBounds = results[0].geometry.bounds;
        if (placeBounds) {
            bounds.value = placeBounds;
        } else {
            bounds.value = results[0].geometry.viewport;
        }
        mapRef.value.map.fitBounds(bounds.value);
    }
  });
};

const showDetail = async (id: any) => {
    isDetail.value = true;
    const response = await axios.get(`/api/report/${id}`);
    if(response) {
        const content = JSON.parse(response.data.content);
        const status = response.data.status;
        const access = response.data.access;
        const date = new Date(response.data.created_at);
        const formattedDate = format(date, "MM/dd/yy hh:mm:ss a");
        const issue_name = content.issue_name;
        const address = content.address;
        const files = content.files;
        delete content.issue_name;
        delete content.address;
        delete content.files;
        delete content.location;
        delete content.place_name;
        detailedReport.value = {issue_name, address, status, access, formattedDate, files, content}
    }
}

watch(() => mapRef.value?.ready,
  (ready) => {
    if (!ready) return;
    
    mapRef.value.map.panTo(location.value);

    // mapRef.value.map.addListener("idle", () => {
    //   location.value = mapRef.value.map.getCenter().toJSON();
    // });
  }
);

onMounted(async () => {
    const response = await axios.get(`/api/workspace/meta/${workspace_id}`);
    if(response) {
        place_name.value = response.data.place_name;
        place_address.value = response.data.place_address;
        form_id.value = response.data.form_id;
        location.value = JSON.parse(response.data.location);
        getBounds();
    }
    const report_response = await axios.get(`/api/report/workspace/${workspace_id}`);
    if(report_response) {
        reports.value = report_response.data.map(item => {
            const content = JSON.parse(item.content);
            locations.value.push(content['location']);
            const date = new Date(item.created_at);
            const formattedDate = format(date, "MM/dd/yy hh:mm:ss a");
            return {
                id: item.id,
                title: content['issue_name'],
                location: content['address'],
                time: formattedDate,
                status: item.status
            }
        });
    }
});
</script>

<template>
    <div class="main">
        <div class="left-sidebar">
            <div v-if="!isDetail">
                <div class="report-list-header">
                    <h1>Service Requests</h1>
                    <div class="report-list-pagenation">
                        <div class="report-list-page">{{ from + ' - ' + to + ' of ' + totalReports }}</div>
                        <div class="report-list-actions">
                            <v-btn class="page-btn" variant="text" :disabled="currentPage === 1" @click="goToPage(currentPage - 1)">&lt;</v-btn>
                            <v-btn class="page-btn" variant="text" :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)">&gt;</v-btn>
                        </div>
                    </div>
                </div>
                <div v-for="item in paginatedReports" :key="item.id" class="report-list">
                    <div @click="showDetail(item.id)" class="report-title">{{ item.title }}</div>
                    <p class="report-location">{{ item.location }}</p>
                    <div class="report-state-time">
                        <div class="report-state">{{ item.status }}</div>
                        <div class="report-time">
                            <v-icon>mdi-clock-time-two-outline</v-icon>
                            {{ item.time }}
                        </div>
                    </div>
                    <p class="report-description">{{ item.description }}</p>
                </div>
            </div>
            <div v-else>
                <div>
                    <v-btn variant="text" class="btn-back" @click="isDetail = false">&lt; Back</v-btn>
                </div>
                <div class="report-detail">
                    <div class="report-title">{{ detailedReport.issue_name }}</div>
                    <p class="report-location">{{ detailedReport.address }}</p>
                    <div class="report-state-time">
                        <div class="report-state">{{ detailedReport.status }}</div>
                        <div class="report-time">
                            <v-icon>mdi-clock-time-two-outline</v-icon>
                            {{ detailedReport.formattedDate }}
                        </div>
                    </div>
                    <v-divider class="my-3"></v-divider>
                    <div v-for="(item, key) in detailedReport.content" :key="key" class="report-content">
                        <h1>{{ key }}</h1>
                        <p>{{ item }}</p>
                    </div>
                    <div class="image-gallery">
                        <div v-for="(file, index) in detailedReport.files" :key="index" class="thumbnail" @click="openImage(file)">
                            <img :src="file" alt="Thumbnail" />
                        </div>
                        <div v-if="selectedImage" class="modal-overlay" @click="selectedImage = null">
                        <div class="modal-content" @click.stop>
                            <img :src="selectedImage" alt="Large Preview" class="large-image" />
                            <button class="close-btn" @click="selectedImage = null">✖</button>
                        </div>
                        </div>
                    </div>
                    <v-divider class="my-3"></v-divider>
                    <div class="report-footer">
                        <div>
                            <v-icon>ph-eye-thin</v-icon>
                            <span>{{ detailedReport.access ? 'Public' : 'Private' }}</span>
                        </div>
                        <div>
                            <v-icon>ph-eye-thin</v-icon>
                            <span>{{ place_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="map-view">
            <GoogleMap
                ref="mapRef"
                api-key="AIzaSyANKjpCtaUho8oy53T63IFl75Ia9qrdDlI"
                :center="location"
                style="width: 100%; height: 100%;"
                :zoom="7"
            >
                <Marker v-for="(location, i) in locations" :key="i" :options="{ position: location }" />
            </GoogleMap>
            <v-btn
                @click="router.push({path: `/report/${form_id}`})"
                class="btn-report"
            >
                <v-icon>mdi-plus-circle-outline</v-icon>
                <span>New Report</span>
            </v-btn>
        </div>
    </div>
</template>

<style>
.main {
    display: flex;
    overflow-y: hidden;
}
.left-sidebar {
    min-width: 400px;
    max-width: 400px;
    /* width: 100%; */
    overflow-y: scroll;
    padding-left: 1rem;
    padding-right: 1rem;
    height: calc(100vh - 60px);
    background: #fff;
}
.map-view {
    height: calc(100vh - 60px);
    max-width: calc(100vw - 400px);
    width: 100%;
    position: relative;
}
.page-btn {
    cursor: pointer;
    min-width: unset;
    margin-right: 10px;
}
.page-btn .v-btn__content {
    font-size: 1.5rem;
}

.image-gallery {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.thumbnail {
  width: 100px;
  height: 100px;
  overflow: hidden;
  cursor: pointer;
  border-radius: 8px;
}

.thumbnail img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Modal styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  position: relative;
  background: white;
  padding: 10px;
  border-radius: 10px;
  z-index: 9999;
}

.large-image {
  max-width: 90vw;
  max-height: 80vh;
  border-radius: 8px;
}

.close-btn {
  position: absolute;
  top: 5px;
  right: 10px;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  font-size: 18px;
  border-radius: 50%;
}
</style>