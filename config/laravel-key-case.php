<?php

return [
    /**
     * Response Key Case
     *
     * This option specifies the case style used for transforming keys in response data.
     *
     * Available options:
     * - 'camel'    Convert keys to camelCase.
     * - 'snake'    Convert keys to snake_case.
     * - 'kebab'    Convert keys to kebab-case.
     * - 'studly'   Convert keys to StudlyCase.
     * - 'lower'    Convert keys to lowercase.
     * - 'upper'    Convert keys to UPPERCASE.
     * - 'ucfirst'  Convert keys to ucfirstCase.
     * - 'ucwords'  Convert keys to ucwords case.
     * - 'singular' Convert keys to singular form.
     * - 'plural'   Convert keys to plural form.
     * - 'slug'     Convert keys to slug form.
     * - 'title'    Convert keys to Title Case.
     */
    'response_case' => env('RESPONSE_CASE', 'camel'),

    /**
     * Request Key Case
     *
     * Specifies what case the request data keys should be transformed to
     *
     */
    'request_case' => env('REQUEST_CASE', 'snake'),
];
