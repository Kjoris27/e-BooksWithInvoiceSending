<!-- resources/views/checkout.blade.php -->

<form action="{{ route('process.checkout') }}" method="post">
    @csrf
    <p>Total Amount: {{ $totalAmount }}</p>

    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" required>

    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" required>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="phone">Phone:</label>
    <input type="tel" name="phone" required>

    <button type="submit">Checkout</button>
</form>
