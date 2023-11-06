<div>
    <section class="ml-4">
        <h1 class="text-2xl font-extrabold">Inscriptions - {{ $tournament->label }}</h1>
        <a href="/tournaments">
            <button class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                Retour aux tournois
            </button>
        </a>
    </section>

    <div class="flex my-8 justify-center">

        <section class="border border-solid border-grey-800 border-2 p-8 m-4 rounded drop-shadow-sm">
            <h2 class="text-l font-bold mr-7">Gestion des joueurs et équipes</h2>

            @if(!$tournament->matches->count())
                <h3 class="font-bold mt-2">Ajout de joueur</h3>
                <input wire:keydown.enter="addPlayer"
                       class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       type="text" wire:model.lazy="newPlayer" placeholder="Joueur">
                <input wire:keydown.enter="addPlayer"
                       class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       type="text" wire:model.lazy="newTeam" placeholder="Equipe">
                <button
                    wire:click="addPlayer"
                    class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                    Ajouter
                </button>
                <br>
            @endif


            @if($tournament->teams->count() < 2)
                <h3 class="font-bold mt-2">Génération d'équipes aléatoires</h3>
                <button
                    wire:click="generateTeamsOf(1)"
                    class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded">
                    Tête-à-têtes
                </button>
                <button
                    wire:click="generateTeamsOf(2)"
                    class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded">
                    Doublettes
                </button>
                <button
                    wire:click="generateTeamsOf(3)"
                    class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded">
                    Triplettes
                </button>
                <br>
                <br>
            @endif
            @if($tournament->teams->first() AND $tournament->teams->first()->label != 'indéfinie')
                {{--            <h3 class="font-bold">Suppression</h3>--}}
                <br>
                <button onclick="confirm('Sur ?') || event.stopImmediatePropagation()"
                        wire:click="resetTeams"
                        class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                    Effacer equipes
                </button>
            @endif
        </section>

        @if($tournament->teams->count() > 1)
            <section class="border border-solid border-grey-800 border-2 p-8 m-4 rounded drop-shadow-sm">
                <h2 class="text-l font-bold">Gestion des poules et matchs</h2>

                @if(!$tournament->matches->count())
                    <h3 class="font-bold">Génération de matchs</h3>
                    <button {{-- onclick="confirm('Sur ?') || event.stopImmediatePropagation()"--}}
                            wire:click="generateBrackets(4)"
                            class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded">
                        Par poules
                    </button>
                    <button {{-- onclick="confirm('Sur ?') || event.stopImmediatePropagation()"--}}
                            wire:click="generatePlayoff"
                            class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded">
                        Élimination directe
                    </button>
                    <button {{-- onclick="confirm('Sur ?') || event.stopImmediatePropagation()"--}}
                            wire:click="generatePrecision"
                            class="bg-transparent hover:bg-orange-500 text-orange-700 font-semibold hover:text-white py-2 px-4 border border-orange-500 hover:border-transparent rounded">
                        Tir de précision
                    </button>
                    <br>
                @else
                    <br>
                    <button onclick="confirm('Sur ?')"
                            wire:click="resetMatches"
                            class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                        Effacer matchs et poules
                    </button>
                @endif
            </section>
        @endif
        @if($tournament->matches->count())
            <section class="m-4">
                <a href="/tournaments/{{ $tournament->id }}/pools">
                    <button class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                        Etape suivante >
                    </button>
                </a>
            </section>
        @endif
    </div>

    <div class="flex justify-center">
        <section class="border border-solid border-grey-800 border-2 p-8 m-4 rounded drop-shadow-sm">
            <h1 class="text-xl font-bold">
                Génération
            </h1>
            <textarea wire:model.lazy="textAreaPlayers" rows="{{ count($tournament->players)+1 }}">

</textarea><br>
            <button wire:click="genTextArea" class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                Sauvegarder
            </button>
        </section>
        <section class="border border-solid border-grey-800 border-2 p-8 m-4 rounded drop-shadow-sm">
            <h2 class="text-xl font-bold">Joueurs ( {{ count($tournament->players) }} )</h2>
            @if(count($tournament->players))
                <ul>
                    @foreach($tournament->players as $player)
                        <li>
                            {{--                        @if($tournament->teams->first() AND $tournament->teams->first()->label == 'indéfinie')--}}
                            <button wire:click="removePlayer({{$player}})"
                                    class="bg-transparent hover:bg-red-500 text-red-500 hover:text-white m-1 px-1 border border-2 border-red-500 hover:border-transparent rounded">
                                X
                            </button>
                            {{--                        @endif--}}
                            {{ $player->name }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Aucun joueur enregistré</p>
            @endif
        </section>


        @if($tournament->teams->first() AND ($tournament->teams->count() > 1 OR $tournament->teams->first()->label != 'indéfinie'))
            <section class="border border-solid border-grey-800 border-2 p-8 m-4 rounded drop-shadow-sm">
                <h2 class="text-xl font-bold">Les équipes ({{ $tournament->teams->count() }})</h2>

                <ul class="">
                    @foreach($tournament->teams->sortBy('id') as $team)
                        <li class=""><strong>{{ $team->label }}</strong>
                            <ul>
                                @foreach($team->players as $player)
                                    <li>{{ $player->name }}</li>
                                @endforeach
                            </ul>
                        </li>
                        <br>
                    @endforeach
                </ul>
            </section>
        @endif



        @if($tournament->brackets->count())
            <section class="border border-solid border-grey-800 border-2 p-8 m-4 rounded drop-shadow-sm">
                <h1 class="text-xl font-bold">
                    Les poules ({{ $tournament->brackets->count() }})
                </h1>
                <ul>
                    @php($cpt=0)
                    @foreach($tournament->brackets->sortBy('label') as $bracket)
                        <li><strong>Poule {{ $bracket->label }}, Terrains {{ ++$cpt }} et {{ ++$cpt }}</strong>
                            @foreach($bracket->bracket_teams as $team)
                                <ul>
                                    <li>{{ $team->label }}</li>
                                </ul>
                        </li>
                    @endforeach
                    <br>
                    @endforeach
                </ul>
            </section>
        @endif

        @if($tournament->matches->count())
            <section class="border border-solid border-grey-800 border-2 p-8 m-4 rounded drop-shadow-sm">
                <h1 class="text-xl font-bold">Les matchs du premier tour</h1>
                @foreach($tournament->brackets as $bracket)
                    <strong>Poule {{ $bracket->label }}</strong>
                    <ul>
                        @foreach($bracket->matches as $match)
                            @if(isset($match->team_1->label))
                                <li>{{ isset($match->team_1->label) ? $match->team_1->label : 'indéfini' }}
                                    vs
                                    {{ isset($match->team_2->label) ? $match->team_2->label : 'indéfini' }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <br>
                @endforeach
            </section>
        @endif
        {{--        @if($tournament->matches->count())--}}
        {{--            <section class="border border-solid border-grey-800 border-2 p-8 m-4 rounded drop-shadow-sm">--}}
        {{--                <h1 class="text-xl font-bold">Les matchs ({{ $tournament->matches->count() }})</h1>--}}
        {{--                @foreach($tournament->brackets as $bracket)--}}
        {{--                    <strong>Poule {{ $bracket->label }}</strong>--}}
        {{--                    <ul>--}}
        {{--                        @foreach($bracket->matches as $match)--}}
        {{--                            <li>{{ isset($match->team_1->label) ? $match->team_1->label : 'indéfini' }}--}}
        {{--                                vs--}}
        {{--                                {{ isset($match->team_2->label) ? $match->team_2->label : 'indéfini' }}--}}
        {{--                                ({{ $match->team_1_score ?: 0 }} - {{ $match->team_2_score ?: 0 }}) </li>--}}
        {{--                        @endforeach--}}
        {{--                    </ul>--}}
        {{--                    <br>--}}
        {{--                @endforeach--}}
        {{--            </section>--}}
        {{--        @endif--}}
    </div>
</div>
