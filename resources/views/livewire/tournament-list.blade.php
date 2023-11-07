<div class="flex justify-center mt-6 flex-wrap md:flex-nowrap">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h1 class="text-xl font-bold mb-3">{{ __('My tournaments') }}</h1>
        <div class="flex gap-3 flex-wrap">
            @forelse($tournaments as $tournament)
                <a href="/tournaments/{{ $tournament->id }}/registration"
                   class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900">{{ $tournament->label }}</h5>
                    <div class="font-normal text-gray-700 dark:text-gray-400">
                        <p>{{ $tournament->date }}</p>
                        <p>{{ $tournament->place }}</p>
                    </div>
                </a>
            @empty
                <p>{{ __('No tournament registered') }}</p>
            @endforelse
        </div>
    </div>
    <div class="w-8"></div>
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h1 class="text-xl font-bold">{{ __('Create new tournament') }}</h1>
        <div class="my-4">
            <ul class="grid w-full md:grid-cols-2 text-center">
                <li>
                    <input type="radio" id="precision" name="team_size" value="precision" class="hidden peer" required>
                    <label wire:click="setTeamSize(0)" for="precision" class="p-2
                    inline-flex items-center justify-between w-full text-gray-500 bg-white border border-gray-500 cursor-pointer
                    peer-checked:border-blue-600 peer-checked:text-white  peer-checked:bg-blue-500  hover:bg-gray-100
                     ">
                        <div class="w-full">{{ __('Precision') }}</div>
                    </label>
                </li>
                <li>
                    <input type="radio" id="simple" name="team_size" value="simple" class="hidden peer" required>
                    <label wire:click="setTeamSize(1)" for="simple" class="p-2
                    inline-flex items-center justify-between w-full text-gray-500 bg-white border border-gray-500 cursor-pointer
                    peer-checked:border-blue-600 peer-checked:text-white  peer-checked:bg-blue-500  hover:bg-gray-100
                     ">
                        <div class="w-full">{{ __('Simple') }}</div>
                    </label>
                </li>
                <li>
                    <input type="radio" id="double" name="team_size" value="double" class="hidden peer">
                    <label wire:click="setTeamSize(2)" for="double" class="p-2
                    inline-flex items-center justify-between w-full text-gray-500 bg-white border border-gray-500 cursor-pointer
                    peer-checked:border-blue-600 peer-checked:text-white  peer-checked:bg-blue-500 hover:bg-gray-100
                     ">
                        <div class="w-full">{{ __('Double') }}</div>
                    </label>
                </li>
                <li>
                    <input type="radio" id="triple" name="team_size" value="triple" class="hidden peer">
                    <label wire:click="setTeamSize(3)" for="triple" class="p-2
                    inline-flex items-center justify-between w-full text-gray-500 bg-white border border-gray-500 cursor-pointer
                    peer-checked:border-blue-600 peer-checked:text-white  peer-checked:bg-blue-500 hover:bg-gray-100
                     ">
                        <div class="w-full">{{ __('Triple') }}</div>
                    </label>
                </li>
            </ul>
        </div>
        <div class="my-4">
            <ul class="grid w-full md:grid-cols-2 text-center">
                <li>
                    <input type="radio" id="bracket" name="has_brackets" value="bracket" class="hidden peer">
                    <label wire:click="setHasBracket(1)" for="bracket" class="p-2
                    inline-flex items-center justify-between w-full text-gray-500 bg-white border border-gray-500 cursor-pointer
                    peer-checked:border-blue-600 peer-checked:text-white  peer-checked:bg-blue-500 hover:bg-gray-100
                     ">
                        <div class="w-full">{{ __('Bracket') }}</div>
                    </label>
                </li>
                <li>
                    <input type="radio" id="playoff" name="has_brackets" value="playoff" class="hidden peer">
                    <label wire:click="setHasBracket(0)" for="playoff" class="p-2
                    inline-flex items-center justify-between w-full text-gray-500 bg-white border border-gray-500 cursor-pointer
                    peer-checked:border-blue-600 peer-checked:text-white  peer-checked:bg-blue-500 hover:bg-gray-100
                     ">
                        <div class="w-full">{{ __('Playoff') }}</div>
                    </label>
                </li>
            </ul>
        </div>

        <div class="mb-4">
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                wire:model="label" name="label" id="label" type="text" placeholder="{{ __('Label') }}">
        </div>
        <div class="mb-4">
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                wire:model="date" name="date" id="date" type="date">
        </div>
        <div class="mb-6">
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                wire:model="place" name="place" id="place" type="text" placeholder="{{ __('Place') }}">
        </div>


        <div class="flex items-center justify-end">
            <button wire:click="createTournament"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="button">
                {{ __('Create') }}
            </button>
        </div>
    </form>
</div>
