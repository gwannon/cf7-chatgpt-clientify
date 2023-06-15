<?php

/**
 * Plugin Name: CF7-chatGPT-Clientify
 * Plugin URI:  https://github.com/gwannon/CF7-chatGPT-Clientify
 * Description: Plugin que lee los textos de los formularios de CF7 y hace preguntas de si/no a ChatGPT para meter una etiqueta u otra a Clientify. 
 * Version:     1.0
 * Author:      Gwannon
 * Author URI:  https://github.com/gwannon/
 * License:     GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cf7cc
 *
 * PHP 8.2.1
 * WordPress 6.2.2
 */

define('CHATGPT_API_KEY', get_option("_cf7cc_api_key"));

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/admin.php';

use Orhanerday\OpenAi\OpenAi;

add_action('wpcf7_before_send_mail', 'cf7cc_mail_sent' );

function cf7cc_mail_sent( $contact_form ) { 	
    $form_ids = explode(",", get_option("_cf7cc_forms_ids"));
    if(in_array($contact_form->id(), $form_ids)) {
        $submission = WPCF7_Submission::get_instance(); 
        $posted_data = $submission->get_posted_data();
        $prompt = cf7cc_generate_prompt ($posted_data);
        $args = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    "role" => "system",
                    "content" => $prompt
                ]
            ],
            'temperature' => 1.0,
            'max_tokens' => 100,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ];
        $open_ai = new OpenAi(CHATGPT_API_KEY);
        $chat = $open_ai->chat($args);
        $d = json_decode($chat, true);
        $content = $args['messages'][0]['content']." -----> ".$d['choices'][0]['message']['content'];
        //file_put_contents(WP_PLUGIN_DIR . '/cf7-chatgpt-clientify/log.txt', $content."\n\n\n");
        foreach (explode(",", get_option("_cf7cc_send_emails")) as $email) {
            /*echo "---".trim($email)."---\n";
            echo "---"."Chat-GPT ".get_bloginfo('url')."---\n";
            echo "---".$content."---\n";*/
            wp_mail(trim($email), "Chat-GPT ".get_bloginfo('url'), $content);
        }
    }
}

function cf7cc_generate_prompt ($posted_data) {
    $prompt = get_option("_cf7cc_prompt");
    foreach (explode(",", get_option("_cf7cc_field_name")) as $label) {
        $label = trim($label);
        $prompt = str_replace("[".$label."]", $posted_data[$label], $prompt);
    }
    return $prompt;
}

/*
    Para darte una idea de cómo funcionan los tokens, aquí hay algunas reglas generales generales para que finalmente puedas entenderlo:

    1 token — 4 caracteres en inglés o casi una palabra
    100 tokens— 75 palabras
    1 o 2 oraciones — 30 tokens
    1 párrafo — 100 tokens
    1.500 palabras — 2.048 tokens— 5,4 páginas
    3.000 palabras — 4.096 tokens— 10,8 páginas
    6.000 palabras — 8.192 — 21,6 páginas
*/

