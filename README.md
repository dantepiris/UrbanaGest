# HuertaEnRed

Esta aplicacion es para los amantes de las huertas que desean compartir la ubicacion de las suyas como tambien tips

Backlog
=======

-En la api falta colocar la fingerprint para que no cachee las peticiones.

-En la api agregar el mensaje de errno y error.

-En la api agregar que la respuesta de los métodos este en un elemento que se pueda recorrer ("response" o "list").

-El motor de plantillas debe poder cargar externs dentro de externs (cuando se cargue un extern se debe analizar ese nuevo recursividad)

-Las urls de los links y redirecciones deben usar la variable de entorno $_ENV["PROJECT_URL"]

-Las variables de sesion deben estar ligadas al nombre del proyecto en env, colocar el reemplazo en los controladores y en los modelos.

-La vista verifyView.html debe mostrar un mensaje de verificación valida y el botón para ir a loguearse o directamente al presionarlo que logue (llevar a perfil)

-se debe enviar un email de validación de usuario al hacer soft delete y volver.

-Evitar que se pueda enviar emails desde la api (bloquear el modelo)