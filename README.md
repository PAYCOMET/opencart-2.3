paycomet-for-opencart
=====================

Módulo de pago de PAYCOMET para OpenCart (v.1.5.x - 2.x)

## Instrucciones de Instalación

1. Descarga el módulo de [aquí](https://github.com/PAYCOMET/opencart/releases/latest)
2. Suba el contenido de las carpetas al directorio root ( / ) de tu instalación opencart.
3. El Módulo esta disponible en Ingles y Castellano. Asegúrese que la carpeta de idiomas ingles es (english o en-gb) y la de castellano (spanish o es-es).  De no ser así, copie el contenido de estas carpetas a la carpeta correspondiente de tu tienda.
4. Para probar el Módulo necesita una cuenta en PAYCOMET. Solicite su cuenta gratuita a info@paycomet.com. Indíquenos la URL/HOST donde tiene instalada la tienda y le proporcionaremos una cuenta Sandbox con tarjetas de prueba para probar la operativa completa del Módulo. La información de tu cuenta la encontrarás en [el panel de gestión de PAYCOMET](https://dashboard.paycomet.com/cp_control/login.php).


Configuración del Módulo
1. Área de Cliente de PAYCOMET
Para que todo funcione correctamente es muy IMPORTANTE configurar las siguientes
opciones.
• URL OK: {RUTA_TIENDA}/index.php?route=payment/paycomet/paymentreturn
• URL KO: {RUTA_TIENDA}/index.php?route=payment/paycomet/paymenterror
• Tipo de notificación del cobro: IMPORTANTE. Debe de estar definido: Notificación por URL ó Notificación por URL y por Email. La Notificación por
Email sólo haría que el proceso no funcionase correctamente.
• URL Notificación: {RUTA_TIENDA}/index.php?route=payment/paycomet/callback
 
2. Opencart
A continuación se explica la configuración del Módulo PAYCOMET para Opencart 2.2.0.
Cuando instalamos el módulo en Opencart, en EXTENSIONES->PAGOS se mostrará el
nuevo Módulo PAYCOMET.COM.
Pulsaremos en Instalar y se realizará la instalación del módulo.
Una vez instalado pulsaremos en Editar para configurar las opciones del Módulo.
Los parámetros de configuración serán los siguientes:
• Estado: Habilitar o deshabitar la opción e pago con PAYCOMET.
• Código Cliente: Figura en el área de clientes de PAYCOMET.
• Numero de Terminal: Figura en el área de clientes de PAYCOMET.
• Contraseña: Figura en el área de clientes de PAYCOMET.
• Terminales disponibles: Seguro, No Seguro, Ambos. El cliente deberá indicar que
tipo de Terminal tiene contratado. Si tiene uno Seguro y otro No Seguro podrá
seleccionar la opción Ambos, para que la primera compra del cliente vaya por
Seguro y el resto por No Seguro.
• Usar 3D Secure: [Si/NO]. Si tiene un terminal Seguro siempre será SI. Si tiene
uno No Seguro siempre será NO. Si Terminales está configurado con “Ambos” y
está activo los pagos con tarjetas tokenizadas irán por NO Seguro. Para nuevas
tarjetas irá con 3D Secure.
• Usar 3D Secure en pagos superiores a: Esta opción se mostrará cuando se haya
seleccionado en Terminales “Ambos”. Si se activa los pagos que superen la cantidad indicada irán por seguro.
Si sólo se dispone de un Terminal y quiere operar con diferentes Monedas también es
posible. Deberá ponerse en contacto con PAYCOMET para que se habilite la opción de
Multimoneda en el terminal. En este caso, el módulo envía el importe total de la
operación al Banco en la moneda del usuario y el Banco realizará la conversión a la
moneda del Terminal.
Opciones de configuración:
• Contraseña del comercio en pagos con Tarjeta Guardada: Si se quiere que se
pida la contraseña del comercio para los pagos con tarjetas almacenadas.
• Administrar tarjetas almacenadas: Si se quiere habilitar el acceso en el apartado
Mi cuenta del Usuario a las tarjetas Tokenizadas. Podrá dar de alta y eliminar sus
tarjetas tokenizadas.

4. Realización del Pedido
A la hora de realizar un pedido en la tienda Opencart, se mostrará como opción de
Pago “Tarjeta de Crédito/Débito (PAYCOMET)”.
4.1.Pago Normal
El iframe de Pago es configurable mediante css. Si lo desea el cliente nos puede
proporcionar una plantilla con su look & feel.
 “Recordar Tarjeta”. Sirve para almacenar el token para futuras compras. Si
seleccionamos esta opción, la siguiente vez que vayamos a pagar en la tienda nos
aparecerá en Pagar con Tarjeta la tarjeta almacenada.

Si está está habilitada la opción “Contraseña del comercio en pagos con Tarjeta
Guardada” se mostrará el campo como en el ejemplo anterior “Clave de la tienda” para
que el usuario introduzca su clave en la tienda para pagar con la tarjetas tokenizada.
Al pulsar en Pagar se verificará estos datos y si es correcto se realizará el pago y el
pedido se finalizará.
Si se pulsa Pagar, si NO está habilitada la opción “Contraseña del comercio en pagos
con Tarjeta Guardada” directamente se realizará el pago y el pedido se finalizará.
Si se desea pagar con otra tarjeta seleccionaremos NUEVA TARJETA para que se
muestren los datos a introducir de la nueva tarjeta.

5. Devoluciones Totales o Parciales
Desde el detalle del Pedido en el Backoffice se podrán realizar devoluciones Totales o
Parciales de los pedidos realizados con este Módulo.
5.1. Devolución Total: Se muestra junto a la transacción el botón “Devolución Total”.
Pulsando se realizará la devolución del importe del pago.
Nota: Si se ha realizado alguna devolución parcial del pedido, el importe total a devolver será el importe restante de devolver como se muestra en la siguiente imagen.
Como se puede observar, una vez se ha realizado la devolución del importe total, ya no
aparecen los botones para realizar más devoluciones.

5.2. Devolución Parcial: Se deberá introducir el importe parcial a devolver y pulsar en
Devolución Parcial.
Una vez realizada la devolución, se mostrará la respuesta, actualizándose el valor tal
devuelto.
Se podrá realizar un número indefinido de devoluciones parciales hasta alcanzar el total del pago.

6. Área de Usuario
Para poder Administrar las tarjetas desde el Área de Usuario en OpenCart 2.X se
deberá instalar la extensión paycomet.ocmod incluido en el directorio raíz del Módulo.
Desde el Instalador de Extensiones de Opencart:
Pulsaremos Cargar y seleccionaremos el fichero paycomet.ocmod.
Una vez hecho esto accedemos a Extesiones->Modificaciones y pulsaremos el Botón
“Refrescar” para que los cambios se apliquen.
Si está todo bien en el Área de Usuario aparecerá una nueva opción: Tarjetas PAYCOMET
como se ve en la siguiente imagen:

Pulsando en dicho enlace se accederá a la gestión Tarjetas Tokenizadas.
Se podrán eliminar las tarjetas tokenizadas y añadir nuevas tarjetas, pulsando Añadir



## Modo real

Con la cuenta sandbox podrás probar el funcionamiento real del módulo. Deberás introduccir las credenciales (Numero Cliente, Terminal y Contraseña) que encontrarás en tu producto de PAYCOMET.

IMPORTANTE: En el Producto de PAYCOMET será necesario definir la URL de Notificación para que los pedidos se procesen correctamente en la tienda.


### Soporte
Si tienes alguna duda o pregunta no tienes más que escribirnos un email a [info@paycomet.com]
