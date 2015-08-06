<?php
return array(
    // set your paypal credential
    'client_id' => 'AaNbvXnLYRKSAwBA9LC23DZrmdthNMY43A68S8v4DpRZThcQ34Y8h3t0Vha1mQyDJV6iqBdqER_KfWAT',
    'secret' => 'EL9Fh7uSVEziUkm7BVOpHBr6XDS2nWFcUpXsNk5C-n-nBgnO9SOiIB5Lf9ejsq1K6qwr01TMVsLKEkkc',

    /**
     * SDK configuration
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',

        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,

        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,

        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',

        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);
