# cf7-chatgpt-clientify
Plugin para WordPress que lee los textos de los formularios de Contact Form 7 y hace preguntas de si/no a ChatGPT para meter una etiqueta u otra a Clientify.

## Instalación
Debes ejecutar composer antes de subirla WordPress para poder descargarte las librerias que hace uso.

## Configuración

Hay un menú en WP-Admin > Ajustes > ChatGPT. Campos:

* ChatGPT Api key.
* IDs de los formularios de CF7: Mete separados por comas las id de los formularios de CF7 que quieres que consulten a ChatGPT.
* Nombres de los campo del formularios para meter en el prompt: Separados por comas. Por ejemplo: your subject,your-message
* Prompt: prompt que vamos a enviar a ChatGPT. Debemos meter las etiquetas de los campos de CF7 que queramos dentro del prompt en la posición que busquemos y entre corchete. Por ejemplo: "Dime si o no si este cleinte quiere hacer un pedido de tartas según este texto: [your subject] - [your-message]"
(mete el nombre del campo como lo metes en el email de notificación [your-message]):
Answer only yes or no if the text between parenthesis is quotation request ([mensaje]).
* Emails de aviso: Separados por comas. Emails a los que se enviará un aviso.

## Por hacer
* Elegir versión de ChatGPT que queires usar.
* Calcular los tokens
* Conectar a la API de Clientify y hacer que según sea la respuesta de ChatGPT meta una etiqueta u otra.
* Diferentes prompts y campos para cada formulario, incluso varias preguntas ChatGPT por .

## Librerías usadas

https://github.com/orhanerday/open-ai
https://github.com/gwannon/PHPClientifyAPI

## Para el calculo de tokens

https://computerhoy.com/son-tokens-chatgpt-cual-limite-ocurre-superas-1240014

Para darte una idea de cómo funcionan los tokens, aquí hay algunas reglas generales generales para que finalmente puedas entenderlo:

1 token — 4 caracteres en inglés o casi una palabra
100 tokens— 75 palabras
1 o 2 oraciones — 30 tokens
1 párrafo — 100 tokens
1.500 palabras — 2.048 tokens— 5,4 páginas
3.000 palabras — 4.096 tokens— 10,8 páginas
6.000 palabras — 8.192 — 21,6 páginas
