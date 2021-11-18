<?php

namespace App\Libraries;

use App\Models\Car;
use PHPHtmlParser\Dom;
use App\Models\Quotation;
use Illuminate\Support\Arr;
use App\Models\GmfCarPackage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class GmFinancial
{
    /** @var string */
    protected $accessToken = null;

    /** @var array */
    protected $sessionCookies = [];

    /** @var App\Models\Quotation */
    protected $quotation;

    /** @var string */
    protected $urlBase = null;

    /**
     * Class constructor method
     *
     * @param  string  $accessToken
     * @param  \App\Models\Quotation  $quotation
     * @return void
     */
    public function __construct(string $accessToken = null, Quotation $quotation = null)
    {
        $this->urlBase = true //App::environment() == 'production'
            ? config('services.gmf.url')
            : config('services.gmf.url_sandbox');

        $this->getAccessToken($accessToken);

        $this->quotation = $quotation;
    }

     /**
     * Method to get a monthly extended warranty payment from the financial service
     *
     * @param  \App\Models\Car  $car
     * @param  int  $percentage
     * @param  int  $term
     * @param  int  $postalCode
     * @return array
     */
    public function getExtendedWarranty(Car $car, int $percentage = 10, int $term = 60, int $postalCode = 64000): array
    {
        $package = $car->getGmfPackage($this);

        $params = [
            'financingTypeId' => 1,
            'initialPercentage' => $percentage,
            'term' => $term,
            'packageTypeId' => 61,
            'BOAKey' => $package->boaKey,
            'invoiceValue' => $car->precio,
            'postalCode' => $postalCode,
            'specialTeam' => 0,
            'SG' => true,
            'adaptations' => 0,
            'optionalCoverage' => "",
        ];

        $response = $this->makeRequest('GetExtendedWarranty', 'post', $params);

        return Arr::get($response->json(), 'services');
    }

    /**
     * Method to get a monthly extended warranty payment from the financial service
     *
     * @param  \App\Models\Car  $car
     * @param  int  $percentage
     * @param  int  $term
     * @param  int  $postalCode
     * @return array
     */
    public function getQuote(Car $car, int $percentage = 10, int $term = 60): ?array
    {
        $quotation = [];

        if ($package = $car->getGmfPackage($this)) {
            $params = [
                'makeId' => $package->makeId,
                'makeCode' => $car->marca,
                'modelCode' => $car->modelo,
                'modelYear' => $car->ano,
                'term' => $term,
                'modelPackageId' => $package->modelPackageId,
                'downpayment' => $percentage * $car->precio / 100,
                'planCategory' => "Plan Especial",
            ];

            $response = $this->makeRequest('GetQuote', 'post', $params);
            $quotation = Arr::first(Arr::get($response->json(), 'quotations'));
        };

        return ! empty($quotation) ? $quotation : null;
    }

    /**
     * Method to get the list of car packages by model and year
     *
     * @param  \App\Models\Car  $car
     * @return array
     */
    public function getCarPackages(Car $car, bool $retry = false): array
    {
        $params = [
            'makeCode' => $car->marca,
            'modelCode' => $car->modelo,
            'modelYear' => $car->ano,
        ];

        $response = $this->makeRequest('GetVehicleModelGroupPackagesByCode', 'post', $params);

        return Arr::get($response->json(), 'vehicleModelGroupPackages');
    }

    /**
     * Return the package that matches with the given car
     *
     * @param  \App\Models\Car  $car
     * @return object
     */
    public function getCarPackage(Car $car): ?object
    {
        // TODO: fix this. Temporary will be looking for the car package
        /*if ($car->gmfPackage)
            return (object) $car->gmfPackage->package;*/

        $packages = $this->getCarPackages($car);

        foreach ($packages as $package) {
            $split = explode(' ', Arr::get($package, 'modelPackageName'));

            if (in_array($car->tipo, $split)) {
                $car->gmfPackage()->updateOrCreate(
                    ['car_id' => $car->id],
                    ['package' => $package]
                );

                return (object) $package;
            }
        }

        return null;
    }

    /**
     * Return the access token to make requests
     *
     * @param  string  $accessToken
     * @return string
     */
    public function getAccessToken(string $accessToken = null): string
    {
        if ($accessToken)
            $this->accessToken = $accessToken;

        if (is_null($this->accessToken)) {
            $response = ($this->makeRequest('/', 'get'));

            $dom = (new Dom())->loadStr($response->body());
            $input = $dom->find('input')[0];

            $this->accessToken = $input->getAttribute('value');
            $this->sessionCookies =  implode('', $response->headers()['Set-Cookie']);
        }

        return $this->accessToken;
    }

    /**
     * Make an HTTP request
     *
     * @param  string  $url
     * @param  string  $method
     * @param  string  $accessToken
     * @param  array  $bodyRequest
     */
    protected function makeRequest($url = '', $method = 'post', $bodyRequest = [], $accessToken = null)
    {
        $endpoint = $this->urlBase . trim($url, '/');

        $accessToken = $accessToken ?? $this->accessToken;

        $headers = $accessToken ?
            [
                "__RequestVerificationToken" => $accessToken,
                'Cookie' => $this->sessionCookies,
            ]
            : [];

        return Http::withHeaders($headers)->{$method}($endpoint, $bodyRequest);
    }
}
