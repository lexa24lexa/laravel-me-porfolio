<x-layout.main>
    <div class="box">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
            @csrf
            <div class="post">
                <h1 class="title is-4">Create a new post</h1>
                <br>
                <h2 class="subtitle is-6 is-italic">
                    Please fill out all the form fields and click 'Save'
                </h2>

                {{-- Title Field --}}
                <div class="field">
                    <label for="title" class="label">Title</label>
                    <div class="control has-icons-right">
                        <input type="text" name="title" id="title" class="input @error('title') is-danger @enderror" value="{{ old('title') }}" autocomplete="title" autofocus>
                        <p class="help is-info" id="titleHint" style="display: none; color: blue;">Please enter the title of your post. Max 50 characters, no special characters.</p>
                        <p class="help is-danger" id="titleHelp">{{ $errors->first('title') }}</p>
                        <p class="help is-success" id="titleSuccess" style="display: none;">Field filled correctly.</p>
                    </div>
                </div>

                {{-- Date Field --}}
                <div class="field">
                    <label for="date" class="label">Happened at:</label>
                    <div class="control has-icons-right">
                        <input type="date" name="date" id="date" class="input @error('date') is-danger @enderror" value="{{ old('date') }}" autocomplete="date">
                        <p class="help is-info" id="dateHint" style="display: none; color: blue;">Please enter the date of the event in the format dd/mm/yyyy.</p>
                        <p class="help is-danger" id="dateHelp">{{ $errors->first('date') }}</p>
                        <p class="help is-success" id="dateSuccess" style="display: none;">Field filled correctly.</p>
                    </div>
                </div>

                {{-- Description Field --}}
                <div class="field">
                    <label for="description" class="label">Description</label>
                    <div class="control has-icons-right">
                        <textarea name="description" id="description" class="textarea @error('description') is-danger @enderror" autocomplete="description" autofocus>{{ old('description') }}</textarea>
                        <p class="help is-info" id="descriptionHint" style="display: none; color: blue;">Please enter the description of your post.</p>
                        <p class="help is-danger" id="descriptionHelp">{{ $errors->first('description') }}</p>
                        <p class="help is-success" id="descriptionSuccess" style="display: none;">Field filled correctly.</p>
                    </div>
                </div>

                <div class="field is-grouped button-container">
                    <div class="control">
                        <button type="submit" class="button is-primary save-button">Save</button>
                    </div>
                    <div class="control">
                        <a href="{{ route('work') }}" class="button is-light cancel-button">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Show hints when input fields are focused
        document.getElementById('title').addEventListener('focus', function() {
            document.getElementById('titleHint').style.display = 'block';
        });

        document.getElementById('title').addEventListener('blur', function() {
            document.getElementById('titleHint').style.display = 'none';
        });

        document.getElementById('date').addEventListener('focus', function() {
            document.getElementById('dateHint').style.display = 'block';
        });

        document.getElementById('date').addEventListener('blur', function() {
            document.getElementById('dateHint').style.display = 'none';
        });

        document.getElementById('description').addEventListener('focus', function() {
            document.getElementById('descriptionHint').style.display = 'block';
        });

        document.getElementById('description').addEventListener('blur', function() {
            document.getElementById('descriptionHint').style.display = 'none';
        });

        // Validate form on submit
        document.getElementById('postForm').addEventListener('submit', function(event) {
            let valid = true;

            // Clear previous error messages
            document.getElementById('titleHelp').textContent = '';
            document.getElementById('dateHelp').textContent = '';
            document.getElementById('descriptionHelp').textContent = '';
            document.getElementById('titleSuccess').style.display = 'none';
            document.getElementById('dateSuccess').style.display = 'none';
            document.getElementById('descriptionSuccess').style.display = 'none';

            // Validate Title
            let title = document.getElementById('title').value;
            let titleRegex = /^[a-zA-Z0-9 ]{1,50}$/;
            if (!titleRegex.test(title)) {
                valid = false;
                document.getElementById('titleHelp').textContent = 'Title must be between 1 and 50 characters and contain no special characters.';
            } else {
                document.getElementById('titleHelp').textContent = '';
                document.getElementById('titleSuccess').style.display = 'block';
            }

            // ✅ Validate Date (now accepts YYYY-MM-DD)
            let date = document.getElementById('date').value;
            let dateRegex = /^\d{4}-\d{2}-\d{2}$/;
            if (!dateRegex.test(date)) {
                valid = false;
                document.getElementById('dateHelp').textContent = 'Date must be in the format YYYY-MM-DD.';
            } else {
                document.getElementById('dateHelp').textContent = '';
                document.getElementById('dateSuccess').style.display = 'block';
            }

            // Validate Description
            let description = document.getElementById('description').value;
            if (description.trim() === '') {
                valid = false;
                document.getElementById('descriptionHelp').textContent = 'Description cannot be empty.';
            } else {
                document.getElementById('descriptionHelp').textContent = '';
                document.getElementById('descriptionSuccess').style.display = 'block';
            }

            if (!valid) {
                event.preventDefault();
            }
        });

        // Validate individual fields on input
        document.getElementById('title').addEventListener('input', function() {
            let title = this.value;
            let titleRegex = /^[a-zA-Z0-9 ]{1,50}$/;
            if (titleRegex.test(title)) {
                document.getElementById('titleHelp').textContent = '';
                document.getElementById('titleSuccess').style.display = 'block';
            } else {
                document.getElementById('titleSuccess').style.display = 'none';
            }
        });

        document.getElementById('date').addEventListener('input', function() {
            let date = this.value;
            let dateRegex = /^\d{4}-\d{2}-\d{2}$/;
            if (dateRegex.test(date)) {
                document.getElementById('dateHelp').textContent = '';
                document.getElementById('dateSuccess').style.display = 'block';
            } else {
                document.getElementById('dateSuccess').style.display = 'none';
            }
        });

        document.getElementById('description').addEventListener('input', function() {
            let description = this.value;
            if (description.trim() !== '') {
                document.getElementById('descriptionHelp').textContent = '';
                document.getElementById('descriptionSuccess').style.display = 'block';
            } else {
                document.getElementById('descriptionSuccess').style.display = 'none';
            }
        });
    </script>
    <style>
        .help.is-success {
            color: green;
        }
    </style>
</x-layout.main>
