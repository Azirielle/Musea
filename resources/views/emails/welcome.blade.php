<x-mail::message>
    # Welcome to Musea!

    Hi {{ $user->first_name }},

    We are thrilled to have you join our community of art lovers and creators. Explore our collection or start sharing
    your own masterpieces today!

    <x-mail::button :url="url('/')">
        Explore Musea
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>