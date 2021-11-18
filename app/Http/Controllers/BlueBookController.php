<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abiside\LibroAzul\Services\LibroAzul;
use App\Http\Resources\BlueBookCatalogResource;
use App\Http\Resources\BlueBookQuotationResource;

class BlueBookController extends Controller
{
    /**
     * Variable to save the libroAzul object to be used on the class
     *
     * @var \Abiside\LibroAzul\LibroAzul
     */
    protected $libroAzul;

    public function __construct()
    {
        $this->libroAzul = new LibroAzul();
    }

    /**
     * Return the list of avaiable years from the blue book service
     *
     * @return \App\Http\Resources\BlueBookCatalogResource
     */
    public function getYears()
    {
        $years = $this->libroAzul->getYears();

        return BlueBookCatalogResource::collection($years);
    }

    /**
     * Return the list of avaiable makes from the given year
     *
     * @return \App\Http\Resources\BlueBookCatalogResource
     */
    public function getMakes(Request $request, int $year)
    {
        $makes = $this->libroAzul->getMakesByYear($year);

        return BlueBookCatalogResource::collection($makes);
    }

    /**
     * Return the list of avaiable models from given year and make
     *
     * @return \App\Http\Resources\BlueBookCatalogResource
     */
    public function getModels(Request $request, int $year, int $make)
    {
        $models = $this->libroAzul->getModelsByYearAndMake($year, $make);

        return BlueBookCatalogResource::collection($models);
    }

    /**
     * Return the list of avaiable versions from given year, make and model
     *
     * @return \App\Http\Resources\BlueBookCatalogResource
     */
    public function getVersions(Request $request, int $year, int $make, int $model)
    {
        $versions = $this->libroAzul->getVersionsByYearMakeAndModel($year, $make, $model);

        return BlueBookCatalogResource::collection($versions);
    }

    /**
     * Return the price for the given version
     *
     * @return
     */
    public function getQuotation(Request $request, int $version)
    {
        $price = $this->libroAzul->getPrice($version);

        return BlueBookQuotationResource::make($price);
    }
}
