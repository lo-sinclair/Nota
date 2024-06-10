<?php

namespace App\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum Status :string implements TranslatableInterface {
	case CONFIRMED = "CONFIRMED";
	case INWORK = "INWORK";
	case SEND = "SEND";
	case AWAITS = "AWAITS";
	case COMPLETED = "COMPLETED";
	case CANCELED = "CANCELED";


	public function trans( TranslatorInterface $translator, ?string $locale = null ): string {
		return match ($this) {
			self::CONFIRMED => 'Подтвержден',
			self::INWORK => 'В работе',
			self::SEND => 'Отправлен',
			self::AWAITS => 'Ожидает получения',
			self::COMPLETED => 'Завершен',
			self::CANCELED => 'Отменен',
		};
	}
}
