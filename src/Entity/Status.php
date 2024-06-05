<?php

namespace App\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum Status :string implements TranslatableInterface {
	case CART = "CART";
	case CONFIRMED = "CONFIRMED";
	case INWORK = "INWORK";
	case SEND = "SEND";
	case COMPLETED = "COMPLETED";
	case CANCELED = "CANCELED";


	public function trans( TranslatorInterface $translator, ?string $locale = null ): string {
		return match ($this) {
			self::CART  => 'В корзине',
			self::CONFIRMED => 'Подтвержден',
			self::INWORK => 'В работе',
			self::SEND => 'Отправлен',
			self::COMPLETED => 'Завершен',
			self::CANCELED => 'Отмнен',
		};
	}
}
