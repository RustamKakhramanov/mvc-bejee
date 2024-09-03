<?php

namespace App\Controllers;

use App\Models\City;
use Core\Controller;
use Core\View;
use Symfony\Component\HttpFoundation\Request;
use App\Services\LanguageService;

class CitiesController extends Controller
{
    public function index(Request $request)
    {
        $language = (new LanguageService)->resolve($request->get('lang', $request->getLocale()));
        $cities = (new City())->getWithGroupByCountryAndRegion($language);

        View::render('cities/index.php', ['data' => $cities]);
    }
}
