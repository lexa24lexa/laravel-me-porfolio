<x-layout.main>
    <div class="container">
        <section class="posts">
            <div class="post-list">
                <h2 class="title">Work</h2>
                @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="create-button">
                    <a href="{{ route('posts.create') }}" class="button">Create</a>
                </div>
                @endif
                @foreach($posts as $post)
                <div class="post">
                    <p>Post: <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></p>
                    <p>Happened at: {{ $post->date }}</p>
                    <p>Description: {{ $post->description }}</p>
                </div>
                @endforeach
            </div>
        </section>
    </div>
</x-layout.main>
