<?php

declare(strict_types=1);

namespace App\Data;

final readonly class ApiError
{
    public function __construct(
        private string $title,
        private string $detail,
        private string $instance,
        private string $code,
        private ?string $link = null,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     detail:string,
     *     instance:string,
     *     code:string,
     *     link:null|string
     * } $data
     */
    public static function fromRequest(array $data): ApiError
    {
        return new ApiError(
            title: $data['title'],
            detail: $data['detail'],
            instance: $data['instance'],
            code: $data['code'],
            link: $data['link'],
        );
    }

    /**
     * @return array{
     *     title:string,
     *     detail:string,
     *     instance:string,
     *     code:string,
     *     link:null|string
     * }
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'detail' => $this->detail,
            'instance' => $this->instance,
            'code' => $this->code,
            'link' => $this->link,
        ];
    }
}
