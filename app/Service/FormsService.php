<?php

use GuzzleHttp\Client;

class FormsService
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.openai.secret'),
            ]
        ]);
    }

    public function createForm(array $data): void
    {
      $forms = Forms::create($data);
        $response = $this->client->post('engines/davinci/completions', [
            'json' => [
                'prompt' => 'Please fill out the form below:' . 
                 PHP_EOL . PHP_EOL .
                'Name: ' . $forms->name . PHP_EOL . 
                'Email: ' . $forms->email . PHP_EOL . 
                'description: ' . $forms->description . PHP_EOL . 
                'hobbies: ' . $forms->hobbies . PHP_EOL .
                'experience: ' . $forms->experience . PHP_EOL .
                'impacto_social: ' . $forms->impacto_social . PHP_EOL . PHP_EOL,

                'max_tokens' => 150,
                'temperature' => 0.3,
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
                'stop' => ['\n', 'Name:',
                 'Email:',
                 'Phone:', 
                 'description:', 
                 'hobbies:', 
                 'experience:',
                 'impacto_social:']
            ]
        ]);
    }
}

/*use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;

class FormHandler {
    private $client;
    private $translator;

    public function __construct($client, $locale) {
        $this->client = $client;
        $this->translator = new Translator($locale);
        $this->translator->addLoader('array', new ArrayLoader());
        $this->translator->addResource('array', [
            'prompt' => 'Por favor, preencha o formulário abaixo:',
            'name' => 'Nome:',
            'email' => 'Email:',
            'description' => 'Descrição:',
            'hobbies' => 'Hobbies:',
            'experience' => 'Experiência:',
            'social_impact' => 'Impacto Social:'
        ], $locale);
    }

    public function createForm(array $data): void {
        $forms = Forms::create($data);
        $prompt = $this->translator->trans('prompt') . PHP_EOL . PHP_EOL .
                  $this->translator->trans('name') . $forms->name . PHP_EOL .
                  $this->translator->trans('email') . $forms->email . PHP_EOL .
                  $this->translator->trans('description') . $forms->description . PHP_EOL .
                  $this->translator->trans('hobbies') . $forms->hobbies . PHP_EOL .
                  $this->translator->trans('experience') . $forms->experience . PHP_EOL .
                  $this->translator->trans('social_impact') . $forms->impacto_social . PHP_EOL . PHP_EOL;

        $response = $this->client->post('engines/davinci/completions', [
            'json' => [
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
    }
}
*/