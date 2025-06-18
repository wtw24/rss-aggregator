<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: int
{
    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/100 */
    case HTTP_CONTINUE = 100;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/101 */
    case HTTP_SWITCHING_PROTOCOLS = 101;

    /**
     * RFC2518
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/102
     */
    case HTTP_PROCESSING = 102;

    /**
     * RFC8297
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/103
     */
    case HTTP_EARLY_HINTS = 103;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/200 */
    case HTTP_OK = 200;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/201 */
    case HTTP_CREATED = 201;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/202 */
    case HTTP_ACCEPTED = 202;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/203 */
    case HTTP_NON_AUTHORITATIVE_INFORMATION = 203;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/204 */
    case HTTP_NO_CONTENT = 204;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/205 */
    case HTTP_RESET_CONTENT = 205;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/206 */
    case HTTP_PARTIAL_CONTENT = 206;

    /**
     * RFC4918
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/207
     */
    case HTTP_MULTI_STATUS = 207;

    /**
     * RFC5842
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/208
     */
    case HTTP_ALREADY_REPORTED = 208;

    /**
     * RFC3229
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/226
     */
    case HTTP_IM_USED = 226;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/300 */
    case HTTP_MULTIPLE_CHOICES = 300;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/301 */
    case HTTP_MOVED_PERMANENTLY = 301;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/302 */
    case HTTP_FOUND = 302;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/303 */
    case HTTP_SEE_OTHER = 303;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/304 */
    case HTTP_NOT_MODIFIED = 304;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/305 */
    case HTTP_USE_PROXY = 305;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/306 */
    case HTTP_RESERVED = 306;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/307 */
    case HTTP_TEMPORARY_REDIRECT = 307;

    /**
     * RFC7238
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/308
     */
    case HTTP_PERMANENTLY_REDIRECT = 308;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/400 */
    case HTTP_BAD_REQUEST = 400;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/401 */
    case HTTP_UNAUTHORIZED = 401;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/402 */
    case HTTP_PAYMENT_REQUIRED = 402;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/403 */
    case HTTP_FORBIDDEN = 403;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/404 */
    case HTTP_NOT_FOUND = 404;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/405 */
    case HTTP_METHOD_NOT_ALLOWED = 405;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/406 */
    case HTTP_NOT_ACCEPTABLE = 406;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/407 */
    case HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/408 */
    case HTTP_REQUEST_TIMEOUT = 408;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/409 */
    case HTTP_CONFLICT = 409;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/410 */
    case HTTP_GONE = 410;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/411 */
    case HTTP_LENGTH_REQUIRED = 411;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/412 */
    case HTTP_PRECONDITION_FAILED = 412;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/413 */
    case HTTP_REQUEST_ENTITY_TOO_LARGE = 413;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/414 */
    case HTTP_REQUEST_URI_TOO_LONG = 414;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/415 */
    case HTTP_UNSUPPORTED_MEDIA_TYPE = 415;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/416 */
    case HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/417 */
    case HTTP_EXPECTATION_FAILED = 417;

    /**
     * RFC2324
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/418
     */
    case HTTP_I_AM_A_TEAPOT = 418;

    /**
     * RFC7540
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/421
     */
    case HTTP_MISDIRECTED_REQUEST = 421;

    /**
     * RFC4918
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422
     */
    case HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * RFC4918
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/423
     */
    case HTTP_LOCKED = 423;

    /**
     * RFC4918
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/424
     */
    case HTTP_FAILED_DEPENDENCY = 424;

    /**
     * RFC-ietf-httpbis-replay-04
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/425
     */
    case HTTP_TOO_EARLY = 425;

    /**
     * RFC2817
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/426
     */
    case HTTP_UPGRADE_REQUIRED = 426;

    /**
     * RFC6585
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/428
     */
    case HTTP_PRECONDITION_REQUIRED = 428;

    /**
     * RFC6585
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/429
     */
    case HTTP_TOO_MANY_REQUESTS = 429;

    /**
     * RFC6585
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/431
     */
    case HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    /**
     * RFC7725
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/451
     */
    case HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/500 */
    case HTTP_INTERNAL_SERVER_ERROR = 500;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/501 */
    case HTTP_NOT_IMPLEMENTED = 501;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/502 */
    case HTTP_BAD_GATEWAY = 502;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/503 */
    case HTTP_SERVICE_UNAVAILABLE = 503;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/504 */
    case HTTP_GATEWAY_TIMEOUT = 504;

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/505 */
    case HTTP_VERSION_NOT_SUPPORTED = 505;

    /**
     * RFC2295
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/506
     */
    case HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;

    /**
     * RFC4918
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/507
     */
    case HTTP_INSUFFICIENT_STORAGE = 507;

    /**
     * RFC5842
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/508
     */
    case HTTP_LOOP_DETECTED = 508;

    /**
     * RFC2774
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/510
     */
    case HTTP_NOT_EXTENDED = 510;

    /**
     * RFC6585
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/511
     */
    case HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;
}
