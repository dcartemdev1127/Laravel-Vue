<script setup lang="ts">
import { ref, watch, defineProps, defineEmits } from "vue";
import { GoogleMap } from 'vue3-google-map';
import GoogleAddressAutocomplete from 'vue3-google-address-autocomplete';
import marker from '@/assets/images/marker.png';

const props = defineProps({coords: Object, address: String});
const emit = defineEmits(['confirm_location']);

const mapRef = ref(null);
const mapCenter = ref(props.coords);
const locationName = ref("Loading...");
const bounds = ref(null);
const isInsideBound = ref(false);

const getAddress = (lat, lng) => {
  const geocoder = new google.maps.Geocoder();
  geocoder.geocode({ location: { lat, lng } }, (results, status) => {
    if (status === "OK" && results[0]) {
      locationName.value = results[0].formatted_address;
    } else {
      locationName.value = "Location not found";
    }
  });
};

const getBounds = async () => {
  const geocoder = new google.maps.Geocoder();
  
  geocoder.geocode({ address: props.address }, (results, status) => {
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

const callbackFunction = (place) => {
  const location = {
    name: place.name,
    address: place.formatted_address,
    latitude: place.geometry.location.lat(),
    longitude: place.geometry.location.lng()
  }
  mapRef.value?.ready && mapRef.value.map.panTo({lat: location.latitude, lng: location.longitude});
}

watch([() => mapRef.value?.ready, props],
  ([ready,props]) => {
    if (!ready) return;
    
    mapCenter.value = mapRef.value.map.getCenter().toJSON();
    getAddress(mapCenter.value.lat, mapCenter.value.lng);
    getBounds();

    mapRef.value.map.addListener("idle", () => {
      mapCenter.value = mapRef.value.map.getCenter().toJSON();
      getAddress(mapCenter.value.lat, mapCenter.value.lng);
      isInsideBound.value = !bounds.value.contains(mapCenter.value);
    });
  }
);

const selectLocation = () => {
  emit('confirm_location',{address: locationName.value, coords: mapCenter.value});
}

</script>

<template>
  <div style="position: relative; width: 100%; height: 100%;">
    <GoogleMap 
      ref="mapRef"
      api-key="AIzaSyANKjpCtaUho8oy53T63IFl75Ia9qrdDlI"
      :center="mapCenter"
      :zoom="13"
      class="map"
    />
    <img class="marker" :src="marker"/>
    <GoogleAddressAutocomplete
      apiKey="AIzaSyANKjpCtaUho8oy53T63IFl75Ia9qrdDlI"
      @callback="callbackFunction"
      class="google-input"
      placeholder="Search Place Name or Address"
    />
    <v-btn :disabled="isInsideBound" @click="selectLocation" class="confirm-btn">Confirm Location</v-btn>
  </div>
</template>

<style scoped>
.confirm-btn {
  position: absolute;
  bottom: 30px;
  left: 0;
  right: 0;
  margin-left: auto;
  margin-right: auto;
  width: 200px;
  background-color: #383838;
  color: #fff;
  fill: #fff;
  font-family: sans-serif;
  font-weight: 500;
  font-size: 14px;
  line-height: 36px;
}
.map {
  width: 100%;
  height: 600px;
}
.marker {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 50px;
  height: 50px;
  transform: translate(-50%, -50%);
}
.google-input {
  position: absolute;
  right: 0;
  left: 0;
  width: 70%;
  top: 0;
  margin-top: 40px;
  margin-right: auto;
  margin-left: auto;
  border: none;
  border-radius: .25rem;
  background-color: #fff;
  font-size: 14px;
  color: #323232;
  height: 40px;
  padding-left: 10px;
  padding-right: 10px;
  z-index: 100;
  box-shadow: 0 4px 8px rgba(8, 4, 1, .12), 0 0 8px rgba(8, 4, 1, .2);
}
</style>
