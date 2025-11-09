<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MAPITFIP</title>

    {{-- 
        ✅ VITE/LARAVEL: La directiva @vite se encarga de buscar y cargar 
        los archivos CSS y JavaScript compilados (incluyendo el hash para cacheo).
    --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- Contenedor donde se montará tu aplicación React --}}
    <div id="root">
        <noscript>Debes habilitar JavaScript para ejecutar esta aplicación.</noscript>
    </div>
</body>
</html>