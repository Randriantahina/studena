<?php

namespace App\Http\Controllers;

use App\DTOs\MatchResultDTO;
use App\Services\MatchmakingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatchmakingController extends Controller
{
    public function __construct(
        private MatchmakingService $matchmakingService,
    ) {}

    public function index(): View
    {
        $results = $this->matchmakingService->findMatchesForAllStudents();

        return view('matchmaking.index', [
            'results' => $results,
        ]);
    }

    public function show(Request $request, int $student): View|JsonResponse
    {
        $result = $this->matchmakingService->findMatchesForStudent($student);

        if (! $result) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Student not found'], 404);
            }

            return back()->withErrors(['student_id' => 'Student not found']);
        }

        if ($request->wantsJson()) {
            return response()->json($result->toArray());
        }

        return view('matchmaking.show', [
            'result' => $result,
        ]);
    }

    public function apiIndex(): JsonResponse
    {
        $results = $this->matchmakingService->findMatchesForAllStudents();

        return response()->json([
            'data' => array_map(fn (MatchResultDTO $result) => $result->toArray(), $results),
        ]);
    }
}
