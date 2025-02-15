<?php
    namespace App\Services;

    use Exception;
    use GeoIp2\Database\Reader;
    use MaxMind\Db\Reader\InvalidDatabaseException;

    class GeoIPService {
        protected Reader $reader;

        /**
         * @throws InvalidDatabaseException
         */
        public function __construct()
        {
            $this->reader = new Reader(storage_path('app/geoip/GeoLite2-Country.mmdb'));
        }

        public function getCountryCodeByIp($ip) {
            try{
                $record = $this->reader->country($ip);
                return $record->country->isoCode;
            } catch(Exception) {
                return null;
            }
        }
    }
