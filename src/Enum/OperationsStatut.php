<?php


namespace App\Enum;

final class OperationsStatut
{
    public const DEMANDE_MODIFICATION      = 'demande_modification';
    public const AUTORISATION_ACCEPTEE     = 'autorisation_acceptee';
    public const AUTORISATION_REFUSEE      = 'autorisation_refusee';
    public const MODIFICATION_DG           = 'modification_dg';
    public const MODIFICATION_SECRETAIRE   = 'modification_secretaire';

    public static function getLabels(): array
    {
        return [
            self::DEMANDE_MODIFICATION       => 'Demande de modification',
            self::AUTORISATION_ACCEPTEE      => 'Autorisation acceptée',
            self::AUTORISATION_REFUSEE       => 'Autorisation refusée',
            self::MODIFICATION_DG            => 'Modification par DG',
            self::MODIFICATION_SECRETAIRE    => 'Modification par Secrétaire',
        ];
    }

    public static function getChoices(): array
    {
        // Inversé pour l’utiliser dans un ChoiceType
        return array_flip(self::getLabels());
    }
}
