# Plugin WordPress cf7-chatgpt-clientify
Plugin para WordPress que lee los textos de los formularios de Contact Form 7 y hace preguntas de si/no a ChatGPT sobre esos textos para meter una etiqueta u otra a Clientify.

## Instalación
Debes ejecutar composer antes de subir el plugin a WordPress para poder descargarte las librerías de las que hace uso.

## Configuración
Hay un menú en WP-Admin > Ajustes > ChatGPT. Campos:

* **ChatGPT Api key.**
* **Clientify API key.**
* **IDs de los formularios de CF7:** Mete separados por comas las id de los formularios de CF7 que quieres que consulten a ChatGPT.
* **Nombre del campo de email:** Nombre del campo del formualrio que contiene el email para poder modificarlo en Clientify.
* **Nombres de los campo del formulario para meter en el prompt:** Separados por comas. Por ejemplo: your subject,your-message
* **Prompt:** Prompt que vamos a enviar a ChatGPT. Debemos meter las etiquetas de los campos de CF7 que queramos dentro del prompt en la posición que queramos y entre corchetes. Por ejemplo: "Dime si o no si este cleinte quiere hacer un pedido de tartas según este texto: [your subject] - [your-message]"
(mete el nombre del campo como lo metes en el email de notificación [your-message]):
Answer only yes or no if the text between parenthesis is quotation request ([mensaje]).
* **Etiqueta de Clientify su ChatGPT responde "yes":** Etiqueta que se va a meter en el usuario si ChatGPT responde 'yes'. Puede dejarse vacio.
* **Etiqueta de Clientify su ChatGPT responde "no":** Etiqueta que se va a meter en el usuario si ChatGPT responde 'no'. Puede dejarse vacio.
* **Emails de aviso:** Separados por comas. Emails a los que se enviará un aviso.

## Por hacer
* Elegir versión de ChatGPT que quieres usar.
* Elegir el número de veces que se va a pedir respuesta a ChatGPT (1 o 3). Con 3 gastamos más tokens pero no aseguramos más.
* Calcular los tokens.
* Diferentes prompts y campos para cada formulario de CF7, incluso varias preguntas para un mismo formulario.

## ¡¡¡Importante!!!
El sistema hace 3 llamadas a ChatGPT y contabiliza las respuestas y se queda con la respuesta más repetida. Si ChatGPT le dice al primer intento "yes", al segun "no" y al tercero "yes", el resultado final es "yes". Todo esto luego peude fallar y como vimos en "Minority Report". Tenedlo en cuenta a la hora de calcular el gasto de la API.

## Librerías usadas

* https://github.com/orhanerday/open-ai
* https://github.com/gwannon/PHPClientifyAPI

## Para el cálculo de tokens

https://computerhoy.com/son-tokens-chatgpt-cual-limite-ocurre-superas-1240014

Para darte una idea de cómo funcionan los tokens, aquí hay algunas reglas generales para que finalmente puedas entenderlo:

* 1 token — 4 caracteres en inglés o casi una palabra
* 100 tokens— 75 palabras
* 1 o 2 oraciones — 30 tokens
* 1 párrafo — 100 tokens
* 1.500 palabras — 2.048 tokens— 5,4 páginas
* 3.000 palabras — 4.096 tokens— 10,8 páginas
* 6.000 palabras — 8.192 — 21,6 páginas
