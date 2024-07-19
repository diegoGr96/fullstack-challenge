<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Errors;

abstract class FinizensError
{
    public abstract function status(): int;

    public abstract function message();

    public abstract function code();

    public function renderError(): array
    {
        $response = [
            'status' => $this->status(),
        ];

        if($this->message()) {
            $response['error']['message'] = $this->message();
        }
        if ($this->code()) {
            $response['error']['code'] = $this->code();
        } 

        return $response;
    }
}
