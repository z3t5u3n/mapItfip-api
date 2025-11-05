<!DOCTYPE html>
<html>
<head>
    <title>Activación de Cuenta en MapItfip</title>
</head>
<body>
    <p>Hola {{ $user->Nombre }},</p>
    <p>Hemos recibido una solicitud para cambiar tu rol en MapItfip. Para activar tu nuevo rol y completar el proceso, por favor haz clic en el siguiente enlace:</p>
    <p><a href="{{ route('activate.account', ['token' => $token]) }}">Activar mi cuenta y nuevo rol</a></p>
    <p>Si no solicitaste este cambio, por favor ignora este correo.</p>
    <p>Este enlace expirará en 24 horas.</p>
    <p>Saludos,</p>
    <p>El equipo de MapItfip</p>
</body>
</html>