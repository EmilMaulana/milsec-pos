<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Pesan WhatsApp</title>
</head>
<body>
    <h1>Kirim Pesan WhatsApp</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @elseif (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('send.message') }}" method="POST">
        @csrf
        <label for="phone">Nomor Telepon:</label>
        <input type="text" id="phone" name="phone" required>
        <br><br>

        <label for="message">Pesan:</label>
        <textarea id="message" name="message" required></textarea>
        <br><br>

        <button type="submit">Kirim Pesan</button>
    </form>
</body>
</html>
