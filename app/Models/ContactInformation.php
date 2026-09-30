<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ContactInformation extends Model
{
    use FlushesSiteCache;

    protected $table = 'contact_information';

    protected $fillable = [
        'school_name', 'phone', 'alt_phone', 'email', 'alt_email', 'physical_address',
        'postal_address', 'office_hours', 'google_maps_url', 'latitude', 'longitude',
    ];

    protected function casts(): array
    {
        return ['latitude' => 'float', 'longitude' => 'float'];
    }

    /** The single row, created with defaults when missing. */
    public static function current(): self
    {
        return static::query()->firstOrCreate([], ['school_name' => config('app.name')]);
    }

    /**
     * URL for the map iframe. Accepts a pasted Google Maps embed URL, or builds one
     * from the coordinates / address.
     */
    protected function mapEmbedUrl(): Attribute
    {
        return Attribute::get(function () {
            $url = trim((string) $this->google_maps_url);
            if (str_contains($url, 'google.com/maps/embed')) {
                return $url;
            }
            if ($this->latitude && $this->longitude) {
                return 'https://maps.google.com/maps?q=' . $this->latitude . ',' . $this->longitude . '&z=15&output=embed';
            }
            if ($this->physical_address) {
                return 'https://maps.google.com/maps?q=' . urlencode($this->physical_address) . '&z=15&output=embed';
            }

            return null;
        });
    }

    /** Link that opens directions in Google Maps. */
    protected function directionsUrl(): Attribute
    {
        return Attribute::get(function () {
            $url = trim((string) $this->google_maps_url);
            if ($url !== '' && ! str_contains($url, '/embed')) {
                return $url;
            }
            if ($this->latitude && $this->longitude) {
                return 'https://www.google.com/maps/search/?api=1&query=' . $this->latitude . ',' . $this->longitude;
            }

            return $this->physical_address
                ? 'https://www.google.com/maps/search/?api=1&query=' . urlencode($this->physical_address)
                : null;
        });
    }
}
