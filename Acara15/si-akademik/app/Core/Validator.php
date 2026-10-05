<?php
// app/Core/Validator.php
class Validator
{
    private array $errors = [];

    public function validate(array $data, array $rules): array
    {
        $this->errors = [];
        foreach ($rules as $field => $ruleStr) {
            foreach (explode('|', $ruleStr) as $rule) {
                $this->applyRule($field, $data[$field] ?? null, $rule);
            }
        }
        return $this->errors;
    }

    private function applyRule(string $field, $value, string $rule): void
    {
        if ($value === null) {
            $value = '';
        }

        $params = [];
        if (str_contains($rule, ':')) {
            [$rule, $raw] = explode(':', $rule, 2);
            $params = explode(',', $raw);
        }
        $label = $this->label($field);

        switch ($rule) {
            case 'required':
                if (trim((string)$value) === '') {
                    $this->errors[$field][] = $label . ' tidak boleh kosong.';
                }
                break;
            case 'min':
                if (mb_strlen(trim((string)$value)) < (int)$params[0]) {
                    $this->errors[$field][] = $label . ' minimal ' . $params[0] . ' karakter.';
                }
                break;
            case 'max':
                if (mb_strlen(trim((string)$value)) > (int)$params[0]) {
                    $this->errors[$field][] = $label . ' maksimal ' . $params[0] . ' karakter.';
                }
                break;
            case 'digits':
                if (!ctype_digit((string)$value)) {
                    $this->errors[$field][] = $label . ' harus berupa angka.';
                }
                break;
            case 'numeric':
                if (!is_numeric($value)) {
                    $this->errors[$field][] = $label . ' harus berupa bilangan.';
                }
                break;
            case 'range':
                if (!is_numeric($value)
                    || (float)$value < (float)$params[0]
                    || (float)$value > (float)$params[1]) {
                    $this->errors[$field][] = $label . ' harus antara ' . $params[0] . ' dan ' . $params[1] . '.';
                }
                break;
            case 'in':
                if (!in_array((string)$value, $params, true)) {
                    $this->errors[$field][] = $label . ' tidak valid.';
                }
                break;
            default:
                break;
        }
    }

    private function label(string $field): string
    {
        $map = [
            'nim'       => 'NIM',
            'nama'      => 'Nama',
            'prodi_id'  => 'Prodi',
            'status'    => 'Status',
            'alamat'    => 'Alamat',
            'kode'      => 'Kode',
            'ka_prodi'  => 'Ketua Prodi',
            'sks'       => 'SKS',
            'username'  => 'Username',
            'password'  => 'Password',
        ];
        return $map[$field] ?? ucfirst($field);
    }
}