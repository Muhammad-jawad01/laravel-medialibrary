<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Gemini</title>
</head>

<body>

    <div class="container py-5">
        <h2 class="mb-4">Gemini Chat</h2>
        <form id="gemini-form" method="POST" action="{{ route('gemini.ask') }}">
            @csrf
            <div class="mb-3">
                <label for="prompt" class="form-label">Your Question</label>
                <textarea name="prompt" id="prompt" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ask Gemini</button>
        </form>
        <div id="gemini-response" class="mt-4"></div>
        <hr>
        <h4>Your Chat History</h4>
        <div id="chat-history" class="list-group"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script>
        document.getElementById('gemini-form').onsubmit = async function(e) {
            e.preventDefault();
            const form = e.target;
            const data = new FormData(form);
            const responseDiv = document.getElementById('gemini-response');
            responseDiv.innerHTML = 'Asking Gemini...';
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': data.get('_token'),
                    'Accept': 'application/json'
                },
                body: data
            });
            const json = await res.json();
            if (json.answer) {
                responseDiv.innerHTML = '<strong>Gemini:</strong> ' + json.answer;
            } else {
                responseDiv.innerHTML = '<span class="text-danger">' + (json.error || 'Error') + '</span>';
            }
            form.reset();
        };

        async function fetchChatHistory() {
            const res = await fetch("{{ route('gemini.history') }}", {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const json = await res.json();
            const historyDiv = document.getElementById('chat-history');
            historyDiv.innerHTML = '';
            json.forEach(interaction => {
                historyDiv.innerHTML += `<div class="list-group-item mb-2">
                    <strong>You:</strong> ${interaction.prompt}<br>
                    <strong>Gemini:</strong> ${interaction.response}
                    <span class="text-muted float-end">${interaction.created_at}</span>
                </div>`;
            });
        }
        setInterval(fetchChatHistory, 5000);
        fetchChatHistory();
    </script>
</body>

</html>
