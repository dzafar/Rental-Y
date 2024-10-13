<?php
namespace App\Application;

class InputSanitizer {
    public function SanitizeInput($input) {
          $input = (array) $input;
          foreach ($input as $key => $value) {
            $input[$key] = (string) $value;
        }
        $input = array_map('trim', $input);
        $input = array_map('htmlspecialchars', $input);
        $input = array_map('strip_tags', $input);
        $input = array_map('escapeshellarg', $input);

        return $input;
    }
}
