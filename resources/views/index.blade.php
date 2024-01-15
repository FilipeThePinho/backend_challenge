<x-layout>
    <div class="flex flex-col gap-4 p-8 rounded-lg shadow-sm bg-white w-[600px]">
        <h1 class="text-2xl font-bold border-b-2 pb-1 border-violet-300">
            Entries
        </h1>

        <div class="flex flex-col gap-2">
            @foreach ($entries->all() as $entry)
            <x-guestbook.entry :title="$entry->title" :content="$entry->content" :email="$entry->submitter_email" :displayName="$entry->submitter_display_name" />
            @endforeach
        </div>

        <div class="flex">
            <a href="{{ route('submit') }}" class="block p-3 rounded bg-violet-700 text-violet-100 font-bold">
                Add an entry
            </a>
        </div>
    </div>
</x-layout>