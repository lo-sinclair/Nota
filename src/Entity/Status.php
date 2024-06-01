<?php

namespace App\Entity;

enum Status :string{
	case CART = "CART";
	case CONFIRMED = "CONFIRMED";
//	case INWORK;
//	case SEND;
//	case COMPLITED;
//	case CANCELED;
}
