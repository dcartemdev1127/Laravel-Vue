<template>
    <div>
      <!-- Google Map -->
      <GMapMap
        :center="mapCenter"
        :zoom="zoomLevel"
        :options="mapOptions"
        style="width: 100%; height: 500px"
        ref="mapRef"
      >
        <!-- Invisible Marker (Position Updates) -->
        <GMapMarker :position="mapCenter" :clickable="false" />
      </GMapMap>
  
      <!-- Search Box -->
      <input ref="searchBoxRef" type="text" placeholder="Search for a city..." class="search-input" />
  
      <!-- Fixed Marker (Stays in Center Visually) -->
      <div class="marker-fixed">
        <img src="@/assets/images/marker.png" alt="Marker" />
      </div>
  
      <!-- White Overlay for Outside the City -->
      <div class="map-overlay"></div>
    </div>
  </template>
  
  <script>
  import { ref, onMounted, watch } from "vue";
  import { useLoadScript, GMapMap, GMapMarker } from "@fawmi/vue-google-maps";
  
  export default {
    components: { GMapMap, GMapMarker },
    setup() {
      const mapCenter = ref({ lat: 40.7128, lng: -74.0060 }); // Default: New York
      const zoomLevel = ref(12);
      const mapRef = ref(null);
      const searchBoxRef = ref(null);
      const cityBounds = ref(null);
  
      const mapOptions = {
        disableDefaultUI: true, // Hide unnecessary UI elements
        restriction: {
          latLngBounds: null, // Will be updated dynamically
          strictBounds: true,
        },
      };
  
      // Load Google Maps API
      const { isLoaded } = useLoadScript({
        googleMapsApiKey: "AIzaSyANKjpCtaUho8oy53T63IFl75Ia9qrdDlI",
        libraries: ["places"],
      });
  
      // Initialize Google Places Autocomplete
      onMounted(() => {
        if (!isLoaded.value) return;
  
        const autocomplete = new google.maps.places.Autocomplete(searchBoxRef.value, {
          types: ["(cities)"],
        });
  
        autocomplete.addListener("place_changed", () => {
          const place = autocomplete.getPlace();
          if (place.geometry) {
            mapCenter.value = {
              lat: place.geometry.location.lat(),
              lng: place.geometry.location.lng(),
            };
  
            // Restrict the map to the selected city
            cityBounds.value = place.geometry.viewport;
            mapOptions.restriction.latLngBounds = cityBounds.value;
  
            // Zoom to fit the selected city
            if (mapRef.value) {
              mapRef.value.fitBounds(cityBounds.value);
            }
          }
        });
      });
  
      // Watch for map center changes
      watch(mapCenter, (newCenter) => {
        if (mapRef.value) {
          mapRef.value.panTo(newCenter);
        }
      });
  
      return { mapCenter, zoomLevel, mapRef, searchBoxRef, mapOptions };
    },
  };
  </script>
  
  <style>
  .search-input {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 300px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    z-index: 10;
  }
  
  .marker-fixed {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -100%);
    pointer-events: none;
    z-index: 10;
  }
  
  .marker-fixed img {
    width: 40px;
    height: 40px;
  }
  
  .map-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.7);
    pointer-events: none;
    z-index: 5;
  }
  </style>
  