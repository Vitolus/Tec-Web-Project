<?php

namespace Classes;

use Exception;

final class Validation
{
    private const SUPPORTED_VALIDATORS = [
        'exists',
        'not-empty',
        'email',
        'max-16-characters',
        'max-32-characters',
        'max-1000-characters',
        'max-64-characters',
        'max-10-characters',
        'max-2-characters',
        'min-1-characters',
        'min-2-characters',
        'min-4-characters',
        'min-5-characters',
        'min-10-characters',
        'min-20-characters',
        'no-number-presence',
        'no-characters-presence',
        'check-for-sex',
    ];

    private array $validators;

    public ?array $errors = null;

    /**
     * @param array $userInput an associative array of fields names as keys and their values
     */
    public function __construct(
        private readonly array $userInput,
    ) {
        foreach (array_keys($userInput) as $keys) {
            $this->validators[$keys] = [];
        }
    }

    /**
     * @param string $fieldName the name of the field to validate
     * @param string $validation a valid validation rule
     */
    public function addValidator(string $fieldName, string $validation)
    {
        if (!in_array($validation, self::SUPPORTED_VALIDATORS)) {
            throw new Exception("Unsupported validator '$validation' for field '$fieldName'");
        }

        $this->validators[$fieldName][] = $validation;

        return $this;
    }

    /**
     * Validates user input with added validators
     * @return true|array true if everything went ok, otherwise an associative array describing which fields had which errors. Eg: `['email' => ['empty', 'email-not-valid'], 'password' => ['empty']]`
     */
    public function validate()
    {
        $errors = [];

        foreach ($this->validators as $fieldName => $validators) {
            if (in_array('exists', $validators) && !array_key_exists($fieldName, $this->userInput)) {
                $errors[$fieldName][] = 'not-exists';
                continue; // if the field is non exsisting then we don't need to check the other conditions.
            }

            if (!array_key_exists($fieldName, $this->validators)) {
                throw new Exception("Field '$fieldName' does not exist");
            }

            $value = $this->userInput[$fieldName];

            if (in_array('not-empty', $validators) && strlen(trim($value)) === 0) {
                $errors[$fieldName][] = 'empty';
                continue; // if the field is empty then we don't need to check the other conditions.
            }

            if (in_array('min-1-characters', $validators) && strlen($value) < 1) {
                $errors[$fieldName][] = "too-short";
                continue;
            }

            if (in_array('min-2-characters', $validators) && strlen($value) < 2) {
                $errors[$fieldName][] = "too-short";
                continue;
            }

            if (in_array('min-4-characters', $validators) && strlen($value) < 4) {
                $errors[$fieldName][] = "too-short";
                continue; // if the field doesn't even satisfy the minimum length requirement then it is useless to check for the maximum length one.
            }

            if (in_array('min-5-characters', $validators) && strlen($value) < 5) {
                $errors[$fieldName][] = "too-short";
                continue; //same as above
            }

            if (in_array('min-10-characters', $validators) && strlen($value) < 10) {
                $errors[$fieldName][] = "too-short";
                continue; // same as above.
            }

            if (in_array('min-20-characters', $validators) && strlen($value) < 20) {
                $errors[$fieldName][] = "too-short";
                continue; // same as above.
            }

            if (in_array('email', $validators) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$fieldName][] = 'email-not-valid';
            }

            if (in_array('max-2-characters', $validators) && strlen($value) > 2) {
                $errors[$fieldName][] = 'too-long';
            }

            if (in_array('max-16-characters', $validators) && strlen($value) > 16) {
                $errors[$fieldName][] = 'too-long';
            }

            if (in_array('max-32-characters', $validators) && strlen($value) > 32) {
                $errors[$fieldName][] = "too-long";
            }

            if (in_array('max-64-characters', $validators) && strlen($value) > 64) {
                $errors[$fieldName][] = "too-long";
            }

            if (in_array('max-1000-characters', $validators) && strlen($value) > 1000) {
                $errors[$fieldName][] = "too-long";
            }

            if (in_array('max-10-characters', $validators) && strlen($value) > 10) {
                $errors[$fieldName][] = "too-long";
            }

            if ((in_array('no-number-presence', $validators)) && preg_match('~[0-9]+~', $value)) {
                $errors[$fieldName][] = "number-presence";
            }

            if ((in_array('no-characters-presence', $validators)) && !is_numeric($value)) {
                $errors[$fieldName][] = "characters-presence";
            }

            if (in_array('check-for-sex', $validators)) {
                switch ($value) {
                    case 'M':
                    case 'F':
                        break;
                    default:
                        $errors[$fieldName][] = 'bad-character';
                }
            }
        }

        $this->errors = $errors;

        return empty($errors) ? true : $errors;
    }
}
