<?php

declare(strict_types=1);

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ValidationErrorResponse',
    title: 'Validation Error Response',
    description: 'Standard validation error response format.',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(
            property: 'errors',
            description: 'An object containing validation errors for each field.',
            type: 'object',
            example: [
                'email' => ['The email has already been taken.'],
                'password' => ['The password confirmation does not match.'],
            ],
            additionalProperties: new OA\AdditionalProperties(
                type: 'array',
                items: new OA\Items(type: 'string')
            )
        ),
    ],
    type: 'object'
)]
final class ValidationErrorResponse {}
