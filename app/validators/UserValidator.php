<?php
namespace App\Validators;

class UserValidator
{
    /** @return array<string,string> campo => mensagem de erro */
    public function validate(array $input): array
    {
        $errors = [];

        $name = $input['name'] ?? '';
        if (!is_string($name) || trim($name) === '') {
            $errors['name'] = 'Informe o nome.';
        } elseif (mb_strlen($name) > 100) {
            $errors['name'] = 'O nome deve ter no máximo 100 caracteres.';
        }

        $email = $input['email'] ?? '';
        if (!is_string($email) || mb_strlen($email) > 150
            || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Informe um e-mail válido.';
        }
        return $errors;

        $lastName = $input['last_name'] ?? '';
        if (!is_string($lastName) || trim($lastName) === '') {
            $errors['last_name'] = 'Informe o sobrenome.';
        } elseif (mb_strlen($lastName) > 100) {
            $errors['last_name'] = 'O sobrenome deve ter no máximo 100 caracteres.';
        }

        $cpf = $input['cpf'] ?? '';
        if (!is_string($cpf) || !$this->validCpf($cpf)) {
            $errors['cpf'] = 'Informe um CPF válido.';
        }

        $birth = $input['birth_date'] ?? '';
        $date = is_string($birth) ? \DateTime::createFromFormat('Y-m-d', $birth) : false;
        if (!$date || $date->format('Y-m-d') !== $birth) {
            $errors['birth_date'] = 'Informe uma data de nascimento válida.';
        } elseif ($birth > date('Y-m-d')) {
            $errors['birth_date'] = 'A data de nascimento não pode estar no futuro.';
        }

    }
    private function validCpf(string $cpf): bool{
        if (!preg_match('/^\d{11}$/', $cpf) || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }
        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
                for ($i = 0; $i < $t; $i++) {
                    $sum += (int) $cpf[$i] * ($t + 1 - $i);
                }
                if ((int) $cpf[$t] !== ((10 * $sum) % 11) % 10) {
                return false;
                }
        }
        return true;
    }
}
