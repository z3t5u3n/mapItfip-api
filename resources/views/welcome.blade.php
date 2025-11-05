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

    {{-- ✅ Cargar CSS principal en HTTPS --}}
    @if ($mainCss)
        <link rel="stylesheet" href="{{ secure_asset('build' . $mainCss) }}">
    @else
        <script>console.warn("⚠️ No se encontró main.css en asset-manifest.json")</script>
    @endif
</head>
<body>
    {{-- Contenedor donde se montará React --}}
    <div id="root">
        <noscript>Debes habilitar JavaScript para ejecutar esta aplicación.</noscript>
    </div>

    {{-- ✅ Cargar JS principal en HTTPS --}}
    @if ($mainJs)
        <script src="{{ secure_asset('build' . $mainJs) }}" defer></script>
    @else
        <script>console.error("❌ No se encontró main.js en asset-manifest.json")</script>
    @endif
</body>
</html>
