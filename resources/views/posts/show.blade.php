<x-layout.main>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/work.css') }}">
    <section class="posts">
        <div class="post">
            <h1>{{$post->title}}</h1>
            <p>Happened at: {{$post->date}}</p>
            <p>Description: {{$post->description}}</p>
            <div class="button-container">
                @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('posts.edit', $post) }}" class="edit-button">Edit</a>
                <a href="{{ route('posts.delete', $post) }}" class="delete-button">Delete</a>
                @endif
                <a href="{{ route('work') }}" class="cancel-button">Cancel</a>
            </div>
        </div>
    </section>
</x-layout.main>
