<form action="{{ route('send.invoice') }}" method="post">
    @csrf
    <label for="email">Enter your e-mail:</label>
    <input type="email" name="email" required>
    <input type="hidden" name="pdfPath" value="{{ $pdfPath }}">
    <button type="submit">Send Invoice</button>
</form>
