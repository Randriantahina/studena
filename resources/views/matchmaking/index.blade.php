<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Matchmaking - Studena</title>
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
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10 fade-in">
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <h1
                    class="text-4xl font-bold text-gray-900 mb-3 bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Système de Matchmaking Tuteurs-Élèves
                </h1>
                <p class="text-gray-600 text-lg">Découvrez les meilleurs tuteurs correspondant à chaque élève</p>
                <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        Algorithme intelligent
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                clip-rule="evenodd" />
                        </svg>
                        Score de compatibilité
                    </span>
                </div>
            </div>
        </div>

        @if (empty($results))
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-100">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 text-lg">Aucun résultat trouvé. Veuillez d'abord exécuter les seeders.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($results as $index => $result)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 fade-in"
                        style="animation-delay: {{ $index * 0.1 }}s">
                        <!-- Student Header -->
                        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-8 py-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h2 class="text-2xl font-bold text-white mb-2 flex items-center gap-3">
                                        <span class="bg-white/20 rounded-full p-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </span>
                                        {{ $result->student->full_name }}
                                    </h2>
                                    <div class="flex flex-wrap items-center gap-4 text-blue-100 text-sm">
                                        <span class="flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                            </svg>
                                            {{ $result->student->level->name }}
                                        </span>
                                        <span class="flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                            </svg>
                                            {{ $result->student->subjects->pluck('name')->join(', ') }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('matchmaking.show', $result->student->id) }}"
                                    class="ml-4 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                    Voir détails
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="p-8">
                            @if ($result->totalMatches === 0)
                                <div class="text-center py-12">
                                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-xl text-gray-500 font-medium">Aucun tuteur trouvé pour cet élève</p>
                                    <p class="text-sm text-gray-400 mt-2">Essayez de modifier les critères de recherche
                                    </p>
                                </div>
                            @else
                                <div class="mb-6 flex items-center justify-between">
                                    <span
                                        class="inline-flex items-center gap-2 bg-green-100 text-green-800 text-sm font-semibold px-4 py-2 rounded-full">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $result->totalMatches }} match(s) trouvé(s)
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    @foreach ($result->matches as $match)
                                        <div
                                            class="match-card border-2 border-gray-200 rounded-xl p-6 bg-gradient-to-br from-white to-gray-50">
                                            <div class="flex justify-between items-start mb-4">
                                                <div class="flex-1">
                                                    <h3
                                                        class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                                                        <span class="bg-indigo-100 text-indigo-600 rounded-full p-2">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                            </svg>
                                                        </span>
                                                        {{ $match->tutor->full_name }}
                                                    </h3>
                                                    <div class="text-sm text-gray-600 space-y-1 ml-9">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-medium text-gray-700">Matières:</span>
                                                            <span
                                                                class="text-gray-900">{{ implode(', ', $match->matchedSubjects) }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-medium text-gray-700">Niveaux:</span>
                                                            <span
                                                                class="text-gray-900">{{ $match->tutor->levels->pluck('name')->join(', ') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right ml-4">
                                                    <div
                                                        class="score-badge text-white text-3xl font-bold px-4 py-2 rounded-xl shadow-lg">
                                                        {{ $match->compatibilityScore }}%
                                                    </div>
                                                    <div class="text-xs text-gray-500 uppercase tracking-wide mt-1">
                                                        Compatibilité</div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-3 gap-3 mt-6">
                                                <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                                    <div class="text-xs text-gray-500 mb-1 font-medium">Matière</div>
                                                    <div
                                                        class="text-sm font-bold text-gray-900 flex items-center gap-1">
                                                        @if (count($match->matchedSubjects) > 0)
                                                            <svg class="w-4 h-4 text-green-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Match
                                                        @else
                                                            <svg class="w-4 h-4 text-red-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Aucun
                                                        @endif
                                                    </div>
                                                </div>
                                                <div
                                                    class="bg-{{ $match->levelMatch ? 'green' : 'red' }}-50 rounded-lg p-3 border border-{{ $match->levelMatch ? 'green' : 'red' }}-100">
                                                    <div class="text-xs text-gray-500 mb-1 font-medium">Niveau</div>
                                                    <div
                                                        class="text-sm font-bold {{ $match->levelMatch ? 'text-green-600' : 'text-red-600' }} flex items-center gap-1">
                                                        @if ($match->levelMatch)
                                                            <svg class="w-4 h-4" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Match
                                                        @else
                                                            <svg class="w-4 h-4" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Non
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="bg-purple-50 rounded-lg p-3 border border-purple-100">
                                                    <div class="text-xs text-gray-500 mb-1 font-medium">Disponibilité
                                                    </div>
                                                    <div class="text-sm font-bold text-gray-900">
                                                        {{ $match->availabilityScore }}%</div>
                                                </div>
                                            </div>

                                            @if (!empty($match->commonAvailabilities))
                                                <div class="mt-5 pt-5 border-t border-gray-200">
                                                    <div
                                                        class="text-xs text-gray-500 mb-3 font-semibold uppercase tracking-wide">
                                                        Créneaux horaires communs:</div>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach ($match->commonAvailabilities as $avail)
                                                            <span
                                                                class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-xs font-medium px-3 py-1.5 rounded-full shadow-sm">
                                                                {{ $avail['day'] }}
                                                                {{ $avail['start'] }}-{{ $avail['end'] }}
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
                @endforeach
            </div>
        @endif
    </div>
</body>

</html>
