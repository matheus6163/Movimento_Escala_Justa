<?php
namespace App\Validators;

class UserValidator
{
    /**
     * @param bool $requirePassword true no cadastro (senha obrigatória);
     *             false na edição (só valida se for preenchida).
     * @return array<string,string> campo => mensagem de erro
     */
    public function validate(array $input, bool $requirePassword = false): array
    {
        $errors = [];

        $name = $input['name'] ?? '';
        if (!is_string($name) || trim($name) === '') {
            $errors['name'] = 'Informe o nome.';
        } elseif (mb_strlen($name) > 100) {
            $errors['name'] = 'O nome deve ter no máximo 100 caracteres.';
        }

        // CORREÇÃO: o "return" ficava no meio e o sobrenome nunca era validado.
        $lastName = $input['last_name'] ?? '';
        if (!is_string($lastName) || trim($lastName) === '') {
            $errors['last_name'] = 'Informe o sobrenome.';
        } elseif (mb_strlen($lastName) > 100) {
            $errors['last_name'] = 'O sobrenome deve ter no máximo 100 caracteres.';
        }

        $email = $input['email'] ?? '';
        if (!is_string($email) || mb_strlen($email) > 150
            || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Informe um e-mail válido.';
        }

        $password = (string) ($input['password'] ?? '');
        if ($requirePassword || $password !== '') {
            if (mb_strlen($password) < 8) {
                $errors['password'] = 'A senha deve ter no mínimo 8 caracteres.';
            } elseif (mb_strlen($password) > 72) {
                $errors['password'] = 'A senha deve ter no máximo 72 caracteres.';
            }
        }

        return $errors;
    }
}