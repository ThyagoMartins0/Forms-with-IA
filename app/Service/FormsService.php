<?php


require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\PhpFileLoader;

class OpenAIFormHandler {
    private $client;
    private $translator;
    private $gTranslator;

    public function __construct($openAIKey, $locale) {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $openAIKey,
                'Content-Type' => 'application/json'
            ]
        ]);

        $this->translator = new Translator($locale);
        $this->translator->addLoader('php', new PhpFileLoader());
        // Carrega os arquivos de tradução
        $this->translator->addResource('php', __DIR__ . '/api/translations/pt.php', 'pt');
        $this->translator->addResource('php', __DIR__ . '/api/translations/en.php', 'en');
        $this->translator->addResource('php', __DIR__ . '/api/translations/es.php', 'es');
        $this->translator->addResource('php', __DIR__ . '/api/translations/fr.php', 'fr');

        // Inicializa o tradutor para o idioma-alvo
        $this->gTranslator = new GoogleTranslate($locale);
    }

    public function createForm(array $data): void {
        $forms = (object) $data;

        // Constrói o prompt baseado no idioma definido
        $prompt = $this->translator->trans('prompt') . PHP_EOL . PHP_EOL .
                  $this->translator->trans('name') . $forms->name . PHP_EOL .
                  $this->translator->trans('email') . $forms->email . PHP_EOL .
                  $this->translator->trans('description') . $forms->description . PHP_EOL .
                  $this->translator->trans('hobbies') . $forms->hobbies . PHP_EOL .
                  $this->translator->trans('experience') . $forms->experience . PHP_EOL .
                  $this->translator->trans('social_impact') . $forms->impacto_social . PHP_EOL . PHP_EOL;

        // Faz a requisição para a API da OpenAI
        $response = $this->client->post('completions', [
            'json' => [
                'model' => 'text-davinci-003',
                'prompt' => $prompt,
                'max_tokens' => 150,
                'temperature' => 0.3,
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
                'stop' => ["\n", 
                           $this->translator->trans('name'), 
                           $this->translator->trans('email'), 
                           $this->translator->trans('description'), 
                           $this->translator->trans('hobbies'), 
                           $this->translator->trans('experience'), 
                           $this->translator->trans('social_impact')]
            ]
        ]);

        // Processa a resposta JSON da OpenAI
        $result = json_decode($response->getBody(), true);
        $gptResponse = $result['choices'][0]['text'] ?? 'No response';

        // Traduz a resposta da OpenAI para o idioma desejado
        $translatedResponse = $this->gTranslator->translate($gptResponse);

        // Exibe a resposta traduzida
        echo $translatedResponse;
    }
}
