<x-layout.main>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/work.css') }}">
    <form action="{{route('posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="post">
            <h1>Edit {{$post -> title}}</h1>
            <div class="field">
                <label for="title" class="label">Title</label>
                <div class="control">
                    <input type="text" name="title"
                        class="input @error('title') is-danger @enderror"
                        value="{{ $post->title }}" autocomplete="title" autofocus>
                    @error('title')
                    @enderror
                </div>
                @error('title')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="date" class="label">Happened at:</label>
                <div class="control">
                    <div class="control">
                        <input type="date" name="date"
                            class="input @error('date') is-danger @enderror"
                            value="{{ $post->date }}" autocomplete="date" autofocus>
                        @error('date')
                        @enderror
                    </div>
                    @error('date')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="field">
                <label for="description" class="label">Description</label>
                <div class="control">
                    <textarea type="text" name="description"
                        class="input @error('description') is-danger @enderror"
                        autocomplete="description" autofocus>{{ $post->description }}</textarea>
                    @error('description')
                    @enderror
                </div>
                @error('description')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="button-container">
                <button type="submit" class="save-button">Save</button>
                <a href="{{ route('work') }}" class="cancel-button">Cancel</a>
            </div>
        </div>
    </form>
</x-layout.main>
