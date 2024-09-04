<?php

namespace App\Controllers;

use Core\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Services\LanguageService;
use App\Models\City;
use Core\View;
use App\Models\Country;
use App\Repositories\CountryRepository;
use App\Services\Adapters\CountryDataAdapter;

class CountryController extends Controller
{
    protected CountryRepository $countryRepository;

    public function __construct()
    {
        $this->countryRepository = new CountryRepository();
    }

    public function index(Request $request): void
    {
        $language = (new LanguageService)->resolve($request->get('lang', $request->getLocale()));
        $data = $this->countryRepository->getWithChildRegionsAndCities($language);

        View::render('countries/index.php', ['data' => $data]);
    }
}
