<?php

namespace App\Services\Contracts;

interface FetchServiceInterface
{
    /*
    * fetches user usage from LiteLLM API over given time period
    * params: start_date, end_date
    */
    public function fetchUsagePeriod($start_date = null, $end_date = null);

    /*
    * fetches user usage of today from LiteLLM API
    */
    public function fetchUsage();
}
