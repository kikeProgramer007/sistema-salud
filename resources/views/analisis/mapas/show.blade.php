<x-app-layout>

    <nav class="flex mb-3" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                        </path>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('mapas.index') }}"
                        class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Mapas
                        de Calor</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Visualizando</span>
                </div>
            </li>
        </ol>
    </nav>
    <div class="mt-2">
        <h1 class="text-xl mb-2 font-semibold text-gray-900 sm:text-2xl dark:text-white">
            Mapa de Calor
        </h1>
        <div class="items-center justify-between block sm:flex pb-4">


            <div class="flex items-center mb-4 sm:mb-0">

                <form class="sm:pr-3" action="#" method="GET">
                    <label for="search-input" class="sr-only">Search</label>
                    <div class="relative w-48 mt-1 sm:w-64 xl:w-96">
                        <input type="text" name="email" id="search-input"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Buscar" />
                    </div>
                </form>
                <div class="flex items-center w-full sm:justify-end">
                    <div class="flex pl-2 space-x-1">
                        <a id="search-button2" onclick="buscarUbi()"
                            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            data-drawer-target="drawer-create-product-default"
                            data-drawer-show="drawer-create-product-default"
                            aria-controls="drawer-create-product-default" data-drawer-placement="right">
                            Buscar
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
                <button type="button" data-refresh onclick="window.location.href = '{{ route('mapas.index') }}'"
                    class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Volver
                </button>

            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="w-full">
                <div id="map" style="width: 100%; height: 500px"></div>
            </div>
        </div>
    </div>

    <x-views>
        Vistas: {{ getPageViews('mapas_ver') }}
    </x-views>

    @push('scripts')
        <script>
      // Script mejorado para el mapa de calor
let map, heatmap, coordinates, infoWindow, markers = [], clusterer;

window.onload = function() {
    initMap();
}

function buscarUbi() {
    const input = document.getElementById("search-input");
    searchLocation(input.value);
}

function initMap() {
    var lati = {!! $mapa->latitud !!}
    var lngi = {!! $mapa->longitud !!}

    coordinates = {!! json_encode($puntos) !!};
    console.log('PUNTOS:', coordinates);

    // Inicializar mapa con controles mejorados
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: { lat: lati, lng: lngi },
        mapTypeId: "satellite",
        gestureHandling: 'cooperative',
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: true,
        streetViewControl: true,
        rotateControl: true,
        fullscreenControl: true
    });

    // InfoWindow único para reutilizar
    infoWindow = new google.maps.InfoWindow();

    // Inicializar heatmap
    initHeatmap();
    
    // Crear marcadores individuales (inicialmente ocultos)
    createMarkers();
    
    // Agregar controles personalizados
    addCustomControls();
    
    // Event listeners
    addEventListeners();
}

function initHeatmap() {
    heatmap = new google.maps.visualization.HeatmapLayer({
        data: getWeightedPoints(),
        map: map,
        radius: 25,
        maxIntensity: 10,
        dissipating: true,
       
    });
}

function getWeightedPoints() {
    var points = [];
    for (var i = 0; i < coordinates.length; i++) {
        if (coordinates[i].latitud && coordinates[i].longitud) {
            // Agregar peso basado en algún criterio (puedes ajustar esto)
            var weight = coordinates[i].peso || coordinates[i].intensidad || 1;
            points.push({
                location: new google.maps.LatLng(coordinates[i].latitud, coordinates[i].longitud),
                weight: weight
            });
        }
    }
    return points;
}

function createMarkers() {
    // Limpiar marcadores existentes
    clearMarkers();
    
    coordinates.forEach((punto, index) => {
        if (punto.latitud && punto.longitud) {
            const marker = new google.maps.Marker({
                position: { lat: parseFloat(punto.latitud), lng: parseFloat(punto.longitud) },
                map: null, // Inicialmente ocultos
                title: punto.enfermedad || `Punto ${index + 1}`,
                icon: {
                    url: 'data:image/svg+xml,' + encodeURIComponent(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FF0000">
                            <circle cx="12" cy="12" r="8" stroke="#FFFFFF" stroke-width="2"/>
                        </svg>
                    `),
                    scaledSize: new google.maps.Size(24, 24)
                }
            });

            // Crear contenido del InfoWindow
            const infoContent = createInfoWindowContent(punto, index);
            
            // Event listener para mostrar InfoWindow
            marker.addListener('click', () => {
                infoWindow.setContent(infoContent);
                infoWindow.open(map, marker);
                
                // Opcional: centrar el mapa en el marcador
                map.panTo(marker.getPosition());
            });

            markers.push(marker);
        }
    });
}

function createInfoWindowContent(punto, index) {
    return `
        <div style="max-width: 300px; font-family: Arial, sans-serif;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px; margin: -8px -8px 12px -8px; border-radius: 4px 4px 0 0;">
                <h3 style="margin: 0; font-size: 16px; font-weight: bold;">
                    📍 ${punto.enfermedad || `Punto ${index + 1}`}
                </h3>
            </div>
            
            <div style="padding: 0 4px;">
                <div style="margin-bottom: 8px;">
                    <strong>🧑 Persona:</strong> ${punto.name || punto.usuario || 'No especificado'}
                </div>
                
                <div style="margin-bottom: 8px;">
                    <strong>📊 Datos:</strong>
                    <ul style="margin: 4px 0; padding-left: 20px;">
                        ${punto.peso ? `<li>Peso: ${punto.peso}</li>` : ''}
                        ${punto.edad ? `<li><strong>Edad:</strong> ${punto.edad}</li>` : ''}
                        ${punto.ubicacion ? `<li>Ubicación: ${punto.ubicacion}</li>` : ''}
                        <li>Lat: ${parseFloat(punto.latitud).toFixed(6)}°</li>
                        <li>Lng: ${parseFloat(punto.longitud).toFixed(6)}°</li>
                    </ul>
                </div>
                
                ${punto.descripcion ? `
                    <div style="margin-bottom: 8px;">
                        <strong>📝 Descripción:</strong><br>
                        <span style="color: #666;">${punto.descripcion}</span>
                    </div>
                ` : ''}
                
                <div style="margin-top: 12px; padding-top: 8px; border-top: 1px solid #eee;">
                    <button onclick="verDetalles(${index})" style="background: #4CAF50; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-right: 8px;">
                        Ver Detalles
                    </button>
                    <button onclick="centrarEnPunto(${punto.latitud}, ${punto.longitud})" style="background: #2196F3; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">
                        Centrar Aquí
                    </button>
                </div>
            </div>
        </div>
    `;
}

function addCustomControls() {
    // Control para alternar entre heatmap y marcadores
    const toggleControl = document.createElement('div');
    toggleControl.className = 'custom-map-control-button';
    toggleControl.innerHTML = `
        <button id="toggle-view" onclick="toggleView()" style="
            background: white; 
            border: 2px solid #fff; 
            border-radius: 3px; 
            box-shadow: 0 2px 6px rgba(0,0,0,.3); 
            cursor: pointer; 
            margin: 8px; 
            padding: 8px 16px; 
            font-size: 14px;
            font-weight: 500;
        ">
            🔥 Mostrar Marcadores
        </button>
    `;
    
    // Control para ajustar intensidad
    const intensityControl = document.createElement('div');
    intensityControl.className = 'custom-map-control-button';
    intensityControl.innerHTML = `
        <div style="background: white; border-radius: 3px; box-shadow: 0 2px 6px rgba(0,0,0,.3); margin: 8px; padding: 8px;">
            <label style="font-size: 12px; font-weight: bold; margin-bottom: 4px; display: block;">Intensidad:</label>
            <input type="range" id="intensity-slider" min="1" max="50" value="25" onchange="changeIntensity(this.value)" style="width: 100px;">
            <span id="intensity-value" style="font-size: 11px; margin-left: 8px;">25</span>
        </div>
    `;
    
    // Control para radio del heatmap
    const radiusControl = document.createElement('div');
    radiusControl.className = 'custom-map-control-button';
    radiusControl.innerHTML = `
        <div style="background: white; border-radius: 3px; box-shadow: 0 2px 6px rgba(0,0,0,.3); margin: 8px; padding: 8px;">
            <label style="font-size: 12px; font-weight: bold; margin-bottom: 4px; display: block;">Radio:</label>
            <input type="range" id="radius-slider" min="10" max="100" value="25" onchange="changeRadius(this.value)" style="width: 100px;">
            <span id="radius-value" style="font-size: 11px; margin-left: 8px;">25</span>
        </div>
    `;

    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(toggleControl);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(intensityControl);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(radiusControl);
}

let showingMarkers = false;

function toggleView() {
    const button = document.getElementById('toggle-view');
    
    if (!showingMarkers) {
        // Mostrar marcadores, ocultar heatmap
        heatmap.setMap(null);
        markers.forEach(marker => marker.setMap(map));
        button.innerHTML = '🗺️ Mostrar Heatmap';
        showingMarkers = true;
    } else {
        // Mostrar heatmap, ocultar marcadores
        markers.forEach(marker => marker.setMap(null));
        heatmap.setMap(map);
        button.innerHTML = '🔥 Mostrar Marcadores';
        showingMarkers = false;
    }
}

function changeIntensity(value) {
    heatmap.set('maxIntensity', parseInt(value));
    document.getElementById('intensity-value').textContent = value;
}

function changeRadius(value) {
    heatmap.set('radius', parseInt(value));
    document.getElementById('radius-value').textContent = value;
}

function addEventListeners() {
 
    // Listener para cambios de zoom
    map.addListener('zoom_changed', () => {
        const zoom = map.getZoom();
        // Ajustar radio del heatmap basado en zoom
        const adaptiveRadius = Math.max(10, Math.min(50, 60 - zoom * 3));
        heatmap.set('radius', adaptiveRadius);
    });
}


function clearMarkers() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
}

function centrarEnPunto(lat, lng) {
    map.setCenter({ lat: parseFloat(lat), lng: parseFloat(lng) });
    map.setZoom(16);
    infoWindow.close();
}

function verDetalles(index) {
    const punto = coordinates[index];
    // Aquí puedes agregar lógica para mostrar más detalles
    // Por ejemplo, abrir un modal o redirigir a otra página
    alert(`Detalles completos del punto:\n\nPersona: ${punto.name || 'No especificado'}\nCoordenadas: ${punto.latitud}, ${punto.longitud}\nEdad: ${punto.edad || 'No especificado'}`);
}

function searchLocation(location) {
    if (location.trim() === "") {
        return;
    }

    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: location }, (results, status) => {
        if (status === "OK" && results.length > 0) {
            // Limpiar marcadores temporales
            clearDrawings();

            // Centrar en la ubicación encontrada
            map.setCenter(results[0].geometry.location);
            map.setZoom(15);

            // Crear marcador de búsqueda
            const searchMarker = new google.maps.Marker({
                position: results[0].geometry.location,
                map: map,
                title: 'Ubicación buscada: ' + location,
                icon: {
                    url: 'data:image/svg+xml,' + encodeURIComponent(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="#FFD700">
                            <path d="M12 2L15.09 8.26L22 9L17 14L18.18 21L12 17.77L5.82 21L7 14L2 9L8.91 8.26L12 2Z" stroke="#FF6600" stroke-width="1"/>
                        </svg>
                    `),
                    scaledSize: new google.maps.Size(32, 32)
                },
                animation: google.maps.Animation.BOUNCE
            });

            // InfoWindow para la búsqueda
            const searchInfo = `
                <div style="font-family: Arial, sans-serif;">
                    <h4>🔍 Ubicación Encontrada</h4>
                    <p><strong>${results[0].formatted_address}</strong></p>
                    <p>Lat: ${results[0].geometry.location.lat().toFixed(6)}°<br>
                    Lng: ${results[0].geometry.location.lng().toFixed(6)}°</p>
                </div>
            `;

            searchMarker.addListener('click', () => {
                infoWindow.setContent(searchInfo);
                infoWindow.open(map, searchMarker);
            });

            drawings.push(searchMarker);

            // Detener la animación después de 3 segundos
            setTimeout(() => {
                searchMarker.setAnimation(null);
            }, 3000);

        } else {
            alert("No se pudo encontrar la ubicación: " + location);
        }
    });
}

function clearDrawings() {
    drawings.forEach(drawing => drawing.setMap(null));
    drawings = [];
}

// Función para exportar datos del mapa
function exportMapData() {
    const mapData = {
        center: map.getCenter().toJSON(),
        zoom: map.getZoom(),
        points: coordinates,
        timestamp: new Date().toISOString()
    };
    
    const dataStr = JSON.stringify(mapData, null, 2);
    const dataBlob = new Blob([dataStr], { type: 'application/json' });
    const url = URL.createObjectURL(dataBlob);
    
    const link = document.createElement('a');
    link.href = url;
    link.download = 'mapa_calor_datos.json';
    link.click();
}

// Variables globales para dibujos
let drawings = [];
        </script>
    @endpush
</x-app-layout>
