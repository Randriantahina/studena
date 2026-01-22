<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matchs pour {{ $result->student->full_name }} - Studena</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .score-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .match-card {
            transition: all 0.3s ease;
        }

        .match-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            transition: width 1s ease-out;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6 fade-in">
            <a href="{{ route('matchmaking.index') }}"
                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition-colors bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
        </div>

        <!-- Student Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-8 fade-in">
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-8 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-white mb-4 flex items-center gap-3">
                            <span class="bg-white/20 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            {{ $result->student->full_name }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-4 text-blue-100">
                            <span class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-lg">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                </svg>
                                <span class="font-semibold">Niveau:</span> {{ $result->student->level->name }}
                            </span>
                            <span class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-lg">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                </svg>
                                <span class="font-semibold">Matières:</span>
                                {{ $result->student->subjects->pluck('name')->join(', ') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 fade-in">
            <div class="p-8">
                @if ($result->totalMatches === 0)
                    <div class="text-center py-16">
                        <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-2xl text-gray-500 font-semibold mb-2">Aucun tuteur trouvé</p>
                        <p class="text-gray-400">Essayez de modifier les critères de recherche ou contactez le support.
                        </p>
                    </div>
                @else
                    <div class="mb-8 flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Tuteurs correspondants</h2>
                            <p class="text-gray-600">Trouvez le tuteur idéal pour {{ $result->student->full_name }}</p>
                        </div>
                        <span
                            class="inline-flex items-center gap-2 bg-green-100 text-green-800 text-sm font-semibold px-5 py-2.5 rounded-full">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $result->totalMatches }} match(s) trouvé(s)
                        </span>
                    </div>

                    <div class="space-y-6">
                        @foreach ($result->matches as $index => $match)
                            <div class="match-card border-2 border-gray-200 rounded-xl p-8 bg-gradient-to-br from-white to-gray-50 fade-in"
                                style="animation-delay: {{ $index * 0.1 }}s">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="flex-1">
                                        <h3 class="text-2xl font-bold text-gray-900 mb-3 flex items-center gap-3">
                                            <span class="bg-indigo-100 text-indigo-600 rounded-full p-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                            {{ $match->tutor->full_name }}
                                        </h3>
                                        <div class="text-sm text-gray-600 space-y-2 ml-12">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-gray-700">Matières enseignées:</span>
                                                <span
                                                    class="text-gray-900">{{ implode(', ', $match->matchedSubjects) }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-gray-700">Niveaux pris en charge:</span>
                                                <span
                                                    class="text-gray-900">{{ $match->tutor->levels->pluck('name')->join(', ') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right ml-6">
                                        <div
                                            class="score-badge text-white text-5xl font-bold px-6 py-4 rounded-2xl shadow-xl">
                                            {{ $match->compatibilityScore }}%
                                        </div>
                                        <div class="text-xs text-gray-500 uppercase tracking-wide mt-2 font-semibold">
                                            Score de compatibilité</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                                    <div class="bg-blue-50 rounded-xl p-5 border-2 border-blue-100">
                                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-3 font-semibold">
                                            Matière</div>
                                        <div class="text-xl font-bold text-gray-900 flex items-center gap-2 mb-2">
                                            @if (count($match->matchedSubjects) > 0)
                                                <svg class="w-6 h-6 text-green-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Match parfait
                                            @else
                                                <svg class="w-6 h-6 text-red-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Aucun match
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            {{ count($match->matchedSubjects) }} matière(s) correspondante(s)
                                        </div>
                                    </div>
                                    <div
                                        class="bg-{{ $match->levelMatch ? 'green' : 'red' }}-50 rounded-xl p-5 border-2 border-{{ $match->levelMatch ? 'green' : 'red' }}-100">
                                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-3 font-semibold">
                                            Niveau</div>
                                        <div
                                            class="text-xl font-bold {{ $match->levelMatch ? 'text-green-600' : 'text-red-600' }} flex items-center gap-2 mb-2">
                                            @if ($match->levelMatch)
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Match parfait
                                            @else
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Pas de match
                                            @endif
                                        </div>
                                    </div>
                                    <div class="bg-purple-50 rounded-xl p-5 border-2 border-purple-100">
                                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-3 font-semibold">
                                            Disponibilité</div>
                                        <div class="text-xl font-bold text-gray-900 mb-2">
                                            {{ $match->availabilityScore }}%</div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-purple-500 h-2.5 rounded-full progress-bar"
                                                style="width: {{ $match->availabilityScore }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-600 mt-2">
                                            {{ count($match->commonAvailabilities) }} créneau(x) commun(s)
                                        </div>
                                    </div>
                                </div>

                                @if (!empty($match->commonAvailabilities))
                                    <div class="mt-6 pt-6 border-t-2 border-gray-200">
                                        <div
                                            class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide flex items-center gap-2">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Créneaux horaires communs
                                        </div>
                                        <div class="flex flex-wrap gap-3">
                                            @foreach ($match->commonAvailabilities as $avail)
                                                <span
                                                    class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md">
                                                    {{ $avail['day'] }} {{ $avail['start'] }}-{{ $avail['end'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
