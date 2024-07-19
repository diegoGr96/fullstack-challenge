<?php

namespace App\Presenters;

use Illuminate\Http\JsonResponse;
use Src\Shared\Domain\Errors\FinizensError;
use Symfony\Component\HttpFoundation\Response;

class ApiPresenter
{
    /** @var float */
    protected $startTime;

    public function __construct()
    {
        $this->startTime = microtime(true);
    }

    public function sendErrorResponse(FinizensError $error): JsonResponse
    {
        return response()->json(
            $error->renderError(),
            $error->status()
        );
    }

    public function sendSuccessResponse($data, int $status = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'execution_time' => microtime(true) - $this->startTime,
            'status' => $status,
        ];
        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }
}
