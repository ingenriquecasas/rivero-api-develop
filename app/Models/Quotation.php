<?php

namespace App\Models;

use Illuminate\Support\Arr;
use App\Models\WarrantyOption;
use App\Libraries\GmFinancial;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $gmfClient;

    protected $fillable = [
        'car_id',
        'payment_method',
        'desired_for',
        'use_purpose',
        'expectation',
        'color',
        'sell_car',
        'entry_percentage',
        'entry_payment',
        'months',
        'monthly_payment',
        'warranty_id',
        'warranty_monthly',
        'quote',
    ];

    protected $casts = [
        'quote' => 'json',
        'sell_car' => 'boolean',
    ];

    protected const FIELDS_FOR_PAYMENTS = [
        'car_id',
        'entry_percentage',
        'months',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function warranty()
    {
        return $this->belongsTo(WarrantyOption::class, 'warranty_id');
    }

    public function gmf()
    {
        $this->gmfClient = $this->gmfClient
            ?? new GmFinancial();

        return $this->gmfClient;
    }

    /**
     * Boot function
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            $model->updatePaymentFields();
        });
    }

    /**
     * Check and update financial fields
     *
     * @return void
     */
    protected function updatePaymentFields(): void
    {
        if ($this->isUpdatedFor(self::FIELDS_FOR_PAYMENTS)) {
            // Set warranty monthly payment
            $warranty = (collect($this->warrantyOptions))->firstWhere('id', $this->warranty_id);
            $this->warranty_monthly = Arr::get($warranty, 'monthlyPrice');

            $this->quote = $this->getGmfQuote();

            $this->monthly_payment = $this->getMonthlyPayment($this->quote);
        }
    }

    /**
     * Return the GMF quote for the current quotation
     *
     * @return array
     */
    public function getGmfQuote()
    {
        $quote = $this->gmf()->getQuote($this->car, $this->entry_percentage, $this->months);

        return $quote;
    }

    /**
     * Return the warranty options for the current quotation
     *
     * @return string
     */
    public function getMonthlyPayment(array $quote = null): string
    {
        $backupMonthlyPayment = $this->calculateMonthlyPayment($this->amountToFinance, $this->months, config('rivero.warranty.interests'));

        return Arr::get($quote ?? $this->getGmfQuote(), 'quote.regularpayment', $backupMonthlyPayment);
    }


    /**
     * Return the warranty options for the current quotation
     *
     * @return array
     */
    public function getWarrantyOptionsAttribute()
    {
        $riveroOptions = WarrantyOption::get()->toArray();
        $interests = config('rivero.warranty.interests');
        $term = $this->months ?? 60;

        foreach($riveroOptions as $key => $option) {
            $price = Arr::get($option, 'price', 0);

            $riveroOptions[$key]['monthlyPrice'] = $this->calculateMonthlyPayment($price, $term, $interests);
        }
        // TODO: Try to include the GMF warranty into the standar quote
        // return $this->gmf()->getExtendedWarranty($this->car, $this->entry_percentage, $this->months);

        return $riveroOptions;
    }

    public function getEntryPaymentAttribute()
    {
        return round($this->car->price * $this->entry_percentage / 100, 2);
    }

    public function getAmountToFinanceAttribute()
    {
        return round($this->car->price - $this->entryPayment, 2);
    }

    /**
     * Calculate the amortization and return the mothly paymennt for the given parameters
     *
     * @param  float  $amount
     * @param  float  $term
     * @param  array  $interestRates
     * @return float
     */
    public function calculateMonthlyPayment($amount, $term, $interestRates): ?float
    {
        $interestRate = 0;

        // Defien the interest for the term
        foreach ($interestRates as $months => $rate) {
            if ($term >= $months) {
                $interestRate = $rate;
            }
        }

        $monthlyRate = $interestRate / 12;
        $payment = $amount * ($monthlyRate / (1 - pow(1 + $monthlyRate, -$term)));

        return round($payment / $term, 2);
    }

    /**
     * Check if the fields were updated and are not empty
     *
     * @param  array  $toCheck
     * @param  array  $updatedFields
     * @return bool
     */
    protected function isUpdatedFor(array $toCheck = [])
    {
        $updated = $this->getDirty();

        $isUpdated = Arr::hasAny($updated, $toCheck);
        $isFilled = true;

        foreach ($toCheck as $field) {
            if (is_null($this->{$field})) $isFilled = false;
        }

        return $isUpdated && $isFilled;
    }
}