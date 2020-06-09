<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use DateTime;
use DateInterval;
use DatePeriod;

class ReservationHelper  
{
	public function getUnavailableDays(Reservation $reservation, ReservationRepository $rr)
	{
		$disabledDays = [];
		// get upper most time boundary for a reservation
		$plus60d = new DateTime();
		$plus60d->add(DateInterval::createFromDateString($_ENV['APP_MAX_RESERVATION_TIME'] . ' day'));
		
		// get all reservation overlaping the now to now +60d periode
		$reservedRanges = $rr->findByTimeRangeExept($reservation->getEdition(), new DateTime(), $plus60d, $reservation);

		// for each periode
		foreach ($reservedRanges as $range ) {
				$interval = DateInterval::createFromDateString('1 day');
				$period = new DatePeriod($range->getBeginingAt(), $interval, $range->getEndingAt());

				// for each day
				foreach ($period as $dt) {
						// push it in an array of disabled days
						array_push($disabledDays, $dt);
				}
		}

		return $disabledDays;
	}
}
