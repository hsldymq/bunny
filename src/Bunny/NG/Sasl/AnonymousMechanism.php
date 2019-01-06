<?php
namespace Bunny\NG\Sasl;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
final class AnonymousMechanism implements SaslMechanismInterface
{

    public function mechanism(): string
    {
        return "ANONYMOUS";
    }

    public function respondTo(?string $challenge): string
    {
        return "";
    }

}