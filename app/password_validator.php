<?php

/**
 * Validador de contraseñas - Sin dependencias externas
 */
class PasswordValidator
{
  /**
   * Valida que la contraseña cumpla con los requisitos de seguridad
   */
  public static function validate($password)
  {
    $errors = [];

    // Longitud mínima
    if (strlen($password) < 8) {
      $errors[] = "La contraseña debe tener al menos 8 caracteres";
    }

    // Al menos una letra mayúscula
    if (!preg_match('/[A-Z]/', $password)) {
      $errors[] = "La contraseña debe contener al menos una letra mayúscula";
    }

    // Al menos una letra minúscula
    if (!preg_match('/[a-z]/', $password)) {
      $errors[] = "La contraseña debe contener al menos una letra minúscula";
    }

    // Al menos un número
    if (!preg_match('/[0-9]/', $password)) {
      $errors[] = "La contraseña debe contener al menos un número";
    }

    // Al menos un carácter especial
    if (!preg_match('/[!@#$%^&*()\-_=+{}\[\]:;,<.>]/', $password)) {
      $errors[] = "La contraseña debe contener al menos un carácter especial (!@#$%^&*()-_=+{}[]:;,<.>)";
    }

    return [
      'valid' => empty($errors),
      'errors' => $errors
    ];
  }

  /**
   * Verifica que la nueva contraseña no sea igual a la anterior
   */
  public static function isDifferent($newPassword, $oldHash)
  {
    return !PasswordHelper::verify($newPassword, $oldHash);
  }

  /**
   * Valida que las contraseñas coincidan
   */
  public static function match($password, $confirmPassword)
  {
    return $password === $confirmPassword;
  }

  /**
   * Calcula fortaleza de contraseña (0-4)
   */
  public static function calculateStrength($password)
  {
    $strength = 0;

    // Longitud
    if (strlen($password) >= 8) $strength++;

    // Mayúscula
    if (preg_match('/[A-Z]/', $password)) $strength++;

    // Minúscula
    if (preg_match('/[a-z]/', $password)) $strength++;

    // Número
    if (preg_match('/[0-9]/', $password)) $strength++;

    // Carácter especial
    if (preg_match('/[!@#$%^&*()\-_=+{}\[\]:;,<.>]/', $password)) $strength++;

    return $strength;
  }
}
