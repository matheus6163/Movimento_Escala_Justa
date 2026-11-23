<?php
namespace App\Validators;

class ContentValidator
{
    /** @return array<string,string> campo => mensagem de erro */
    public function validate(array $input): array
    {
        $errors = [];

        $title = $input['title'] ?? '';
        if ($title === '') {
            $errors['title'] = 'Informe o título.';
        } elseif (mb_strlen($title) > 150) {
            $errors['title'] = 'O título deve ter no máximo 150 caracteres.';
        }

        $caption = $input['caption'] ?? '';
        if ($caption === '') {
            $errors['caption'] = 'Informe a legenda.';
        } elseif (mb_strlen($caption) > 300) {
            $errors['caption'] = 'A legenda deve ter no máximo 300 caracteres.';
        }

        if (!in_array($input['status'] ?? '', ['draft', 'published'], true)) {
            $errors['status'] = 'Situação inválida.';
        }
        return $errors;
    }
}
