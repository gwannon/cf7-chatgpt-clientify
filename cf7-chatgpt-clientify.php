<?php

/**
 * Plugin Name: CF7-chatGPT-Clientify
 * Plugin URI:  https://github.com/gwannon/cf7-chatgpt-clientify
 * Description: Plugin que lee los textos de los formularios de CF7 y hace preguntas de a ChatGPT para meter una etiqueta u otra a Clientify según la respuesta. 
 * Version:     0.6
 * Author:      Gwannon
 * Author URI:  https://github.com/gwannon/
 * License:     GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cf7cc
 *
 * PHP 8.2.1
 * WordPress 6.2.2
 */

 ini_set("display_errors", 1);

require __DIR__ . '/vendor/autoload.php';
use Orhanerday\OpenAi\OpenAi;
use Gwannon\PHPClientifyAPI\contactClientify;
//use Gwannon\PHPActiveCampaignAPI\contactAC;
//use Gwannon\PHPActiveCampaignAPI\curlAC;


require __DIR__ . '/admin.php';

define("CLIENTIFY_API_URL", "https://api.clientify.net/v1");
define("CLIENTIFY_LOG_API_CALLS", false);
define('CHATGPT_API_KEY', get_option("_cf7cc_chatgpt_api_key"));
define('CLIENTIFY_API_KEY', get_option("_cf7cc_clientify_api_key"));

//Cargamos el multi-idioma
function cf7cc_plugins_loaded() {
    load_plugin_textdomain('cf7cc', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
}
add_action('plugins_loaded', 'cf7cc_plugins_loaded', 0 );




/* Chequeamos el envío de correos */ 
add_action('wpcf7_before_send_mail', 'cf7cc_mail_sent' );
function cf7cc_mail_sent( $contact_form ) { 	
    $forms = get_option("_cf7cc_forms"); 
    foreach ($forms as $form) {

        if($contact_form->id() == $form['form_id']) {
            $submission = WPCF7_Submission::get_instance(); 
            $posted_data = $submission->get_posted_data();
            $prompt = cf7cc_generate_prompt ($posted_data, $form['prompt']);
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
            $counter = 0;
            $responses = [];
            //echo "---".$prompt."---\n";
            
            $open_ai = new OpenAi(CHATGPT_API_KEY);
            $chat = $open_ai->chat($args);
            $d = json_decode($chat, true);
            $response = '';
            if(isset($d['choices'][0]['message']['content'])) {
                $response = cf7cc_clean_chatGPT_response($d['choices'][0]['message']['content']);
            }

            //Guardamos log
            //file_put_contents(WP_PLUGIN_DIR . '/cf7-chatgpt-clientify/log.txt', $content."\n\n\n");

            //Avisamos a los admins
            $content = $prompt." -----> ".$response;
            //echo "---".$content."---\n";
            $admins = explode(",", get_option("_cf7cc_send_emails"));
            if(count($admins) > 0) {
                foreach ($admins as $email) {
                    wp_mail(trim($email), "Chat-GPT ".get_bloginfo('url'), $content);
                }
            }

            //Conectamos con Clientify y metemos las etiquetas adecuadas 
            $email_label = $form['email_field'];
            $email = $posted_data[$email_label];
            if ($form['response1'] == $response) $tag = $form['tag1'];
            else if ($form['response2'] == $response) $tag = $form['tag2'];
            else if ($form['response3'] == $response) $tag = $form['tag3'];
            else $tag = "";
            $contact = new contactClientify($email, true); //Si no existe se crea
            if($tag != '' && !$contact->hasTag($tag)) {
                //echo "---Añadir tag: ".$tag."---\n";
                $contact->addTag($tag);
                $contact->update();
            }
        }
    }
}

function cf7cc_clean_chatGPT_response($string) {
    $string = preg_replace('/[^a-z]/i','',strtolower($string));
    return $string;
}

function cf7cc_generate_prompt ($posted_data, $prompt) {
     foreach ($posted_data as $label => $value) {
        $label = trim($label);
        $text = str_replace(["\r\n", "\r", "\n", "\t"], ' ', trim($value));
        $prompt = str_replace("[".$label."]", $text, $prompt);
    }
    return $prompt;
}
