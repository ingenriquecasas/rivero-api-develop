<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Awobaz\Compoships\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function scopeSortableBy($query, array $sortableBy, string $defaultOrder = null)
    {
        // TODO: Improve request validations for orderBy parameters
        $request = request();

        $orderString = $request->get('orderBy', $defaultOrder);
        $orderAttrs = explode(',', $orderString);
        $orderBy = [];

        foreach ($orderAttrs as $attr) {
            list($prop,$order) = explode(':', $attr);

            $prop = Arr::get($sortableBy, $prop, $prop);

            $orderBy[$prop] = $order;
        }

        $orderBy = Arr::only($orderBy, $sortableBy);

        foreach ($orderBy as $field => $order) {
            $query = $query->orderBy($field, $order);
        }
    }

    public function scopeSearch($query, $searchTerms = null, $searchInFields = [])
    {
        return $query->when(!! $searchTerms, function($query) use ($searchTerms, $searchInFields) {
            foreach ($searchInFields as $k => $field) {
                $query = $k == 0
                    ? $query->where($field, 'like', '%' . $searchTerms . '%')
                    : $query->orWhere($field, 'like', '%' . $searchTerms . '%');
            }

            return $query;
        });
    }

    public function publicUrl(string $url)
    {
        return config('filesystems.cdn.cloudfront.domain') . '/' . ltrim($url, '/');
    }
}
