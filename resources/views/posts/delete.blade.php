<x-layout.main>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/work.css') }}">
    <form action="{{ route('posts.destroy', $post) }}" method="POST">
        @csrf
        @method('DELETE')
        <h1>{{ $post->title }}</h1>
        <p>Are you sure you want to delete this post?</p>
        <div class="button-container">
            <button type="submit" class="delete-button">Delete</button>
            <a href="{{ route('work') }}" class="cancel-button">Cancel</a>
        </div>
    </form>
</x-layout.main>
