<div>
    <form wire:submit.prevent="createOrUpdate">
        <div>
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input wire:model.blur="title" id="title" name="title" type="text" class="mt-1 block w-full"
                required autofocus autocomplete="title" />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="description" :value="__('Description')" />
            <x-text-input wire:model.blur="description" id="description" name="description" type="text"
                class="mt-1 block w-full" required autofocus autocomplete="description" />
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="status" :value="__('Status')" />
            <select name="status" id="status" wire:model.blur="status"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Select status</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        <x-primary-button class="mt-4">
            {{ $taskId ? 'Update' : 'Create' }}
        </x-primary-button>
    </form>
    <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
        @forelse ($tasks as $task)
            <div class="p-6 flex space-x-2" wire:key="{{ $task->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-800">{{ $task->user->name }}</span>
                            <small class="ml-2 text-sm text-gray-600">{{ $task->status }}</small>
                        </div>
                        @if ($task->user->is(auth()->user()))
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link wire:click="editTask({{ $task->id }})">
                                        {{ __('Edit') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link wire:click="deleteTask({{ $task->id }})"
                                        wire:confirm="Are you sure to delete this task?">
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        @endif
                    </div>
                    <p class="mt-4 text-lg text-gray-900">{{ $task->title }}</p>
                </div>
            </div>
        @empty
            <p class="p-6 text-center text-gray-600">No tasks yet</p>
        @endforelse
    </div>
</div>
