@php
    // Ruta absoluta al manifest
    $assetManifestPath = public_path('build/asset-manifest.json');

    // Leer y decodificar el manifest si existe
    $manifest = file_exists($assetManifestPath)
        ? json_decode(file_get_contents($assetManifestPath), true)
        : [];

    // Obtener los archivos principales
    $mainCss = $manifest['files']['main.css'] ?? null;
    $mainJs  = $manifest['files']['main.js'] ?? null;
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MAPITFIP</title>

    {{-- ✅ VITE CARGA AUTOMÁTICAMENTE LOS ARCHIVOS CSS/JS --}}
    {{-- La directiva @vite busca los archivos en el 'manifest.json' generado por la compilación --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- Contenedor donde se montará React --}}
    <div id="root">
        <noscript>Debes habilitar JavaScript para ejecutar esta aplicación.</noscript>
    </div>
</body>
</html>
