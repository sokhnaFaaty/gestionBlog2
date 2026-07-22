<?php


function validations(array $datas, array $rules ,?callable $uniqueCheck = null): array {
    $errors = [];

    foreach ($rules as $field => $fieldRules) {
        // Récupérer la valeur du champ ou une chaîne vide si inexistant
        $value = isset($datas[$field]) ? $datas[$field] : '';
        
        // Séparer les différentes règles du champ )
        $rulesArray = explode('|', $fieldRules);

        foreach ($rulesArray as $rule) {
            
            if ($rule === 'required') {
                if (!isset($datas[$field]) || trim((string)$value) === '') {
                    $errors[$field] = "Ce champ est obligatoire.";
                    break; 
                }
            }

            // Si le champ n'est pas requis et qu'il est vide, on ignore les autres validations
            if (trim((string)$value) === '') {
                continue;
            }

            if ($rule === 'email') {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "L'adresse email n'est pas valide.";
                }
            }
             if ($rule === 'unique') {
                if ($uniqueCheck !== null && $uniqueCheck($value)) {
                    $errors[$field] = "Cette valeur est déjà utilisée.";
                }
            }

            if ($rule === 'numeric') {
                if (!is_numeric($value)) {
                    $errors[$field] = "Ce champ doit être un nombre.";
                }
            }

            if ($rule === 'string') {
                if (!is_string($value) || is_numeric($value)) {
                    $errors[$field] = "Ce champ doit être une chaîne de caractères.";
                }
            }
        }
    }

    return $errors;
}

function validate(array $errors): bool {
    return count($errors) === 0;
}