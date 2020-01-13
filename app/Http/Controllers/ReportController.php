<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\ReportService;
use App\Http\Requests;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ReportController extends Controller
{
    private $reportService;
    protected $helper;

    public function __construct(ReportService $reportService, Helper $helper)
    {
        $this->reportService = $reportService;
        $this->helper = $helper;
    }

    public function getSalesByDateRange($page, $limit, $start_date, $end_date, $filter=null, $search_by=null, $search_value=null)
    {
        $start = (intval($page) < 1 ? 0 : intval($page) - 1) * intval($limit);
        $end = intval($limit) + $start;
        $filterStr = '';
        if (!is_null($filter) && ($filter != 'all')) {
            $filterStr = " AND status = '$filter'";
        }
        
        if(!is_null($search_by)) {
            $search_value = str_replace('\\', '/', $search_value);
            Log::info('Search By '.$search_by.' Search Value: '.$search_value);
            switch ($search_by) {
                case 'email': {
                    $filterStr .= " AND form_details like '%\"email_address\":\"$search_value%'";
                    break;
                }
                case 'policy_number': {
                    $filterStr .= " AND policy_no LIKE '%$search_value%'";
                    break;
                }
                case 'registration_number': {
                    $filterStr .= " AND registration_no LIKE '%$search_value%'";
                    break;
                }
            }
        }
        Log::info($filterStr);
        $response = $this->reportService->getSalesByDateRange($start, $end, $start_date, $end_date, $filterStr);
        return $response;
    }

    public function getSalesByProducts($page, $limit, $start_date, $end_date, $filter=null, $search_by=null, $search_value=null) {
        $response = $this->reportService->getSalesByProducts();
        return $response;
    }

    public function getSalesByAgents($page, $limit, $start_date, $end_date, $filter=null, $search_by=null, $search_value=null) {
        $response = $this->reportService->getSalesByAgents();
        return $response;
    }

    public function getActivePolicies() {
        $response = $this->reportService->getActivePolicies();
        return $response;
    }

    public function getExpiringPolicies($days) {
        $response = $this->reportService->getExpiringPolicies($days);
        return $response;
    }

    public function getExpiredPolicies() {
        $response = $this->reportService->getExpiredPolicies();
        return $response;
    }

}
